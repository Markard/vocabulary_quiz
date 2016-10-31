<?php

namespace Vocabulary\QuizBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Vocabulary\QuizBundle\Entity\AnswerQuiz;
use Vocabulary\QuizBundle\Entity\Quiz;
use Vocabulary\QuizBundle\Entity\QuizQuestion;
use Vocabulary\QuizBundle\Entity\WrongAnswer;

class WrongAnswerRepository extends EntityRepository
{
    public function logFail(QuizQuestion $question, AnswerQuiz $answer)
    {
        $entity = new WrongAnswer();
        $entity->setQuestion($question);

        if ($question->getType() === QuizQuestion::TYPE_EN) {
            $entity->setEnglishWordId($question->getQuestionWordId());
            $entity->setRussianWordId($answer->answer_id);
        } else {
            if ($question->getType() === QuizQuestion::TYPE_RU) {
                $entity->setEnglishWordId($answer->answer_id);
                $entity->setRussianWordId($question->getQuestionWordId());
            }
        }

        $this->save($entity);
    }

    public function save(WrongAnswer $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }
}
