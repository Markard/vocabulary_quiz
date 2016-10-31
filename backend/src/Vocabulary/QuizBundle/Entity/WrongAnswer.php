<?php

namespace Vocabulary\QuizBundle\Entity;

/**
 * WrongAnswer
 */
class WrongAnswer
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $questionId;

    /**
     * @var int
     */
    private $englishWordId;

    /**
     * @var int
     */
    private $russianWordId;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    // ---------------------------------------------------------------------------------------------------------
    // Relations
    // ---------------------------------------------------------------------------------------------------------

    /**
     * @var QuizQuestion
     */
    private $question;

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
     * Set questionId
     *
     * @param integer $questionId
     *
     * @return WrongAnswer
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;

        return $this;
    }

    /**
     * Get questionId
     *
     * @return int
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * Set englishWordId
     *
     * @param integer $englishWordId
     *
     * @return WrongAnswer
     */
    public function setEnglishWordId($englishWordId)
    {
        $this->englishWordId = $englishWordId;

        return $this;
    }

    /**
     * Get englishWordId
     *
     * @return int
     */
    public function getEnglishWordId()
    {
        return $this->englishWordId;
    }

    /**
     * Set russianWordId
     *
     * @param integer $russianWordId
     *
     * @return WrongAnswer
     */
    public function setRussianWordId($russianWordId)
    {
        $this->russianWordId = $russianWordId;

        return $this;
    }

    /**
     * Get russianWordId
     *
     * @return int
     */
    public function getRussianWordId()
    {
        return $this->russianWordId;
    }/**
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
     * prePersist Event
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));

        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    // ---------------------------------------------------------------------------------------------------------
    // Question relation
    // ---------------------------------------------------------------------------------------------------------

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion(QuizQuestion $question)
    {
        $this->question = $question;
    }

}

