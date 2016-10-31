<?php

namespace Vocabulary\QuizBundle\Repository;

use Vocabulary\QuizBundle\Entity\QuizQuestion;

class QuizQuestionRepository extends \Doctrine\ORM\EntityRepository
{
    public function save(QuizQuestion $question)
    {
        $this->getEntityManager()->persist($question);
        $this->getEntityManager()->flush($question);
    }
}
