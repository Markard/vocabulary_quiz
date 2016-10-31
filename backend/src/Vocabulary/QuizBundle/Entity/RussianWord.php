<?php

namespace Vocabulary\QuizBundle\Entity;

/**
 * RussianWord
 */
class RussianWord implements WordInterface
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
     * @var EnglishWord
     */
    private $englishWord;


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
     * @return RussianWord
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
     * @return RussianWord
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
     * @return RussianWord
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
     * @return EnglishWord
     */
    public function getEnglishWord()
    {
        return $this->englishWord;
    }

    /**
     * @param EnglishWord $englishWord
     */
    public function setEnglishWord($englishWord)
    {
        $this->englishWord = $englishWord;
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
}

