<?php

declare(strict_types = 1);

namespace Vocabulary\QuizBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Vocabulary\QuizBundle\Entity\WordInterface;

abstract class TranslationRepository extends EntityRepository
{
    /**
     * @param $id
     *
     * @return int
     */
    abstract public function getTranslationWordId($id);

    /**
     * @return ClassMetadata
     */
    abstract public function getTranslationMetadata();

    /**
     * @param bool $fromTranslationTable
     *
     * @return WordInterface
     */
    public function getRandomWord($fromTranslationTable = false)
    {
        if ($fromTranslationTable) {
            $tableName = $this->getTranslationMetadata()->getTableName();
        } else {
            $tableName = $this->getClassMetadata()->getTableName();
        }

        $alias = 'word';
        $rsm = $this->createResultSetMappingBuilder($alias);

        $sql = <<<DQL
SELECT {$rsm->generateSelectClause()}
FROM `{$tableName}` AS `{$alias}`
  INNER JOIN (SELECT RAND() * (SELECT MAX(`id`) FROM `{$tableName}`) AS `id`) AS `t2`
WHERE `{$alias}`.`id` >= `t2`.`id`
ORDER BY `{$alias}`.`id`
LIMIT 1;
DQL;

        return $this->getEntityManager()->createNativeQuery($sql, $rsm)->getSingleResult();
    }

    /**
     * @param $answerId
     * @param bool $fromTranslationTable
     *
     * @return WordInterface
     */
    public function getWord($answerId, $fromTranslationTable = false)
    {
        if ($fromTranslationTable) {
            $repository = $this
                ->getEntityManager()
                ->getRepository($this->getTranslationMetadata()->rootEntityName);
        } else {
            $repository = $this;
        }

        return $repository->findOneBy(['id' => $answerId]);
    }

    public function getPossibleAnswers($maxPossibleAnswers, $answerId)
    {
        $answer = $this->getWord($answerId, true);
        $answers = [
            $answerId => ['id' => $answer->getId(), 'word' => $answer->getWord()]
        ];

        while(count($answers) < $maxPossibleAnswers) {
            do {
                $word = $this->getRandomWord(true);
            } while (isset($answers[$word->getId()]));
            $answers[$word->getId()] = ['id' => $word->getId(), 'word' => $word->getWord()];
        }

        shuffle($answers);

        return $answers;
    }
}