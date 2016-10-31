<?php

namespace Vocabulary\QuizBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Vocabulary\QuizBundle\Entity\Quiz;

class QuizRepository extends EntityRepository
{
    public function save(Quiz $quiz)
    {
        $this->getEntityManager()->persist($quiz);
        $this->getEntityManager()->flush($quiz);
    }    
}
