<?php

declare(strict_types = 1);

namespace Vocabulary\QuizBundle\Repository;

use Vocabulary\QuizBundle\Entity\EnglishWord;
use Vocabulary\QuizBundle\Entity\RussianWord;

class RussianWordRepository extends TranslationRepository
{
    /**
     * {@inheritdoc}
     */
    public function getTranslationWordId($id)
    {
        /** @var RussianWord $rWord */
        $rWord = $this->find(['id' => $id]);

        return $rWord->getEnglishWord()->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationMetadata()
    {
        return $this
            ->getEntityManager()
            ->getClassMetadata(EnglishWord::class);
    }
}
