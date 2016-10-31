<?php

declare(strict_types = 1);

namespace Vocabulary\QuizBundle\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vocabulary\QuizBundle\Entity\AnswerQuiz;
use Vocabulary\QuizBundle\Entity\Quiz;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class QuestionController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  section="Quiz",
     *  description="Проверяем правильность ответа. Если ответ не верный, записываем его в базу данных.
     * Если общее количество ошибок в тесте больше 3-х завершаем тест.",
     *  headers={
     *      {
     *           "name"="Authorization",
     *           "description"="Bearer token",
     *           "required"=true
     *      }
     *  },
     *  input={
     *     "class"="Vocabulary\QuizBundle\Type\AnswerQuizType"
     *  },
     *  output={
     *     "class"="Vocabulary\QuizBundle\Entity\Quiz",
     *     "groups"={"answer"},
     *     "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *  },
     *  statusCodes = {
     *      200={"Ответ."},
     *      401={"Не удалось аутентифицировать в системе."},
     *      404={"Тест не найден."}
     *  }
     * )
     *
     * @ParamConverter("quiz", options={"id" = "quizId"}, class="VocabularyQuizBundle:Quiz")
     *
     * @param Quiz $quiz
     * @param Request $request
     *
     * @return Response
     */
    public function postAnswerAction(Quiz $quiz, Request $request)
    {
        $typeClass = $this->container->getParameter('vocabulary.quiz.answer.type.class');
        $form = $this->createForm($typeClass, new AnswerQuiz());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quiz = $this->container
                ->get('vocabulary.quiz.handler')
                ->answer($quiz, $form->getData());

            $context = (new Context())->addGroup('answer');
            $view = View::create($quiz, Response::HTTP_OK)
                ->setContext($context);
        } else {
            $view = View::create(['form' => $form], Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="Quiz",
     *  resource=false,
     *  description="Получаем информацию о текущем вопросе. Если data пустой объект, то считаем, что тест закончен.",
     *  headers={
     *      {
     *           "name"="Authorization",
     *           "description"="Bearer token",
     *           "required"=true
     *      }
     *  },
     *  output={
     *     "class"="Vocabulary\QuizBundle\Entity\Quiz",
     *     "groups"={"get_question"},
     *     "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *  },
     *  statusCodes = {
     *      200={"Удалось успешно получить вопрос и варианты ответа."},
     *      401={"Не удалось аутентифицировать в системе."},
     *      404={"Тест не найден."},
     *      400={"Тест уже завершен."}
     *  }
     * )
     *
     * * @ParamConverter("quiz", options={"id" = "quizId"}, class="VocabularyQuizBundle:Quiz")
     *
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function getCurrentQuestionAction(Quiz $quiz)
    {
        $quiz = $this->container
            ->get('vocabulary.quiz.handler')
            ->getQuestionWithAnswers($quiz, $this->getParameter('max_question_possible_answers'));

        $context = (new Context())->addGroup('get_question');
        $view = View::create($quiz, Response::HTTP_OK)->setContext($context);

        return $this->handleView($view);
    }
}