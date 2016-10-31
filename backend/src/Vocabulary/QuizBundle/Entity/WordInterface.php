<?php

declare(strict_types = 1);


namespace Vocabulary\QuizBundle\Entity;


interface WordInterface
{
    public function getId();

    public function getWord();

    public function getTranslationId();
}