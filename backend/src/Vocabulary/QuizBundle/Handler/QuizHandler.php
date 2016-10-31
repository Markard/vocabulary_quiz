<?php

declare(strict_types = 1);

namespace Vocabulary\QuizBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Vocabulary\QuizBundle\Entity\AnswerQuiz;
use Vocabulary\QuizBundle\Entity\EnglishWord;
use Vocabulary\QuizBundle\Entity\Quiz;
use Vocabulary\QuizBundle\Entity\QuizQuestion;
use Vocabulary\QuizBundle\Entity\RussianWord;
use Vocabulary\QuizBundle\Entity\WordInterface;
use Vocabulary\QuizBundle\Entity\WrongAnswer;
use Vocabulary\QuizBundle\Repository\QuizQuestionRepository;
use Vocabulary\QuizBundle\Repository\TranslationRepository;
use Vocabulary\QuizBundle\Repository\QuizRepository;
use Vocabulary\QuizBundle\Repository\WrongAnswerRepository;
use Vocabulary\UserBundle\Entity\User;

class QuizHandler
{
    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * @var QuizRepository
     */
    protected $repository;

    /**
     * @var QuizQuestionRepository
     */
    protected $questionRepository;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var TokenStorageInterface
     */
    protected $securityTokenStorage;

    public function __construct(ObjectManager $om, ContainerInterface $container, TokenStorageInterface $tokenStorage)
    {
        $this->om = $om;
        $this->repository = $om->getRepository(Quiz::class);
        $this->questionRepository = $om->getRepository(QuizQuestion::class);
        $this->container = $container;
        $this->securityTokenStorage = $tokenStorage;
    }

    public function create($failsNumber, $questionsNumber): Quiz
    {
        $quiz = new Quiz();
        /** @var User $user */
        $user = $this->securityTokenStorage->getToken()->getUser();
        $quiz->setUserId($user->getId());
        $quiz->setScore(0);
        $quiz->setCurrentFailsNumber(0);
        $quiz->setFailsNumber($failsNumber);
        $quiz->setCurrentQuestionNumber(0);
        $quiz->setQuestionsNumber($questionsNumber);

        $this->repository->save($quiz);

        return $quiz;
    }

    public function answer(Quiz $quiz, AnswerQuiz $answer): Quiz
    {
        if ($quiz->getIsFinished()) {
            return $quiz;
        }

        /** @var QuizQuestion $lastQuestion*/
        if (!$lastQuestion = $quiz->getLastQuestion()->first()) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Question does not exist.');
        }

        $repository = $this->getWordRepositoryForQuestion($lastQuestion->getType());
        $answerId = $repository->getTranslationWordId($lastQuestion->getQuestionWordId());
        if ($answerId === $answer->answer_id) {
            $quiz->setIsAnswerRight(true);

            if (!$lastQuestion->getIsAnswered()) {
                $quiz->setScore($quiz->getScore() + 1);
            }
            $quiz->setCurrentQuestionNumber($quiz->getCurrentQuestionNumber() + 1);
        } else {
            $quiz->setIsAnswerRight(false);
            $quiz->setCurrentFailsNumber($quiz->getCurrentFailsNumber() + 1);

            /** @var WrongAnswerRepository $wrongAnswerRepository */
            $wrongAnswerRepository = $this->om->getRepository(WrongAnswer::class);
            $wrongAnswerRepository->logFail($lastQuestion, $answer);
        }
        $lastQuestion->setIsAnswered(true);
        $this->questionRepository->save($lastQuestion);
        $this->repository->save($quiz);

        return $quiz;
    }

    /**
     * @param string $type
     *
     * @return TranslationRepository
     */
    private function getWordRepositoryForQuestion(string $type)
    {
        if ($type === QuizQuestion::TYPE_RU) {
            return $this->om->getRepository(RussianWord::class);
        }

        if ($type === QuizQuestion::TYPE_EN) {
            return $this->om->getRepository(EnglishWord::class);
        }

        throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Question has incorrect type.');
    }

    public function getQuestionWithAnswers(Quiz $quiz,  int $maxPossibleAnswers)
    {
        if ($quiz->getIsFinished()) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Quiz is finished');
        }

        /** @var QuizQuestion $lastQuestion */
        $questions = $quiz->getQuestions();
        $lastQuestion = $questions->last();
        if ($lastQuestion
            && (!$lastQuestion->getIsAnswered() || !$questions->count() - 1 === $quiz->getCurrentQuestionNumber())) {
            $question = $lastQuestion;
            $repository = $this->getWordRepositoryForQuestion($question->getType());
            $word = $repository->getWord($question->getQuestionWordId());
        } else {
            $question = new QuizQuestion();
            $question->setIsAnswered(false);

            $question->setRandomType();
            $repository = $this->getWordRepositoryForQuestion($question->getType());
            do {
                $word = $repository->getRandomWord();
                $question->setQuestionWordId($word->getId());
            } while ($quiz->hasSameQuestion($question));
            $quiz->addQuestion($question);
        }
        $quiz->setCurrentQuestion([
            'id' => $word->getId(),
            'word' => $word->getWord(),
        ]);

        $possibleAnswers = $repository->getPossibleAnswers($maxPossibleAnswers, $word->getTranslationId());
        $quiz->setPossibleAnswers($possibleAnswers);

        $this->repository->save($quiz);

        return $quiz;
    }
}