<?php

declare(strict_types = 1);

namespace Vocabulary\QuizBundle\Repository;

use Vocabulary\QuizBundle\Entity\EnglishWord;
use Vocabulary\QuizBundle\Entity\RussianWord;

class EnglishWordRepository extends TranslationRepository
{
    /**
     * {@inheritdoc}
     */
    public function getTranslationWordId($id)
    {
        /** @var EnglishWord $eWord */
        $eWord = $this->find(['id' => $id]);

        return $eWord->getRussianWord()->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationMetadata()
    {
        return $this
            ->getEntityManager()
            ->getClassMetadata(RussianWord::class);
    }
}
