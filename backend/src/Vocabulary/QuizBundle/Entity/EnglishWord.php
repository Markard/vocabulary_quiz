<?php

namespace Vocabulary\QuizBundle\Entity;

/**
 * EnglishWord
 */
class EnglishWord implements WordInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $word;

    /**
     * @var int
     */
    private $translationId;
    
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var RussianWord
     */
    private $russianWord;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set word
     *
     * @param string $word
     *
     * @return EnglishWord
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Get translationId
     * 
     * @return int
     */
    public function getTranslationId()
    {
        return $this->translationId;
    }

    /**
     * Set translationId
     * 
     * @param int $translationId
     */
    public function setTranslationId($translationId)
    {
        $this->translationId = $translationId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return EnglishWord
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return EnglishWord
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * prePersist Event
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));

        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    /**
     * @return RussianWord
     */
    public function getRussianWord()
    {
        return $this->russianWord;
    }

    /**
     * @param RussianWord $russianWord
     */
    public function setRussianWord($russianWord)
    {
        $this->russianWord = $russianWord;
    }
}

