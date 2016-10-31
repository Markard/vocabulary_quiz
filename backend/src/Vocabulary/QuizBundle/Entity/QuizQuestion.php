<?php

namespace Vocabulary\QuizBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * QuizQuestion
 */
class QuizQuestion
{
    const TYPE_RU = 'ru';
    const TYPE_EN = 'en';

    const TYPE_MAP = [
        self::TYPE_EN,
        self::TYPE_RU,
    ];

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $quizId;

    /**
     * @var bool
     */
    private $isAnswered;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $questionWordId;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     */
    private $createdAt;

    // ---------------------------------------------------------------------------------------------------------
    // Relations
    // ---------------------------------------------------------------------------------------------------------

    /**
     * @var Quiz
     */
    private $quiz;

    /**
     * @var WrongAnswer[]
     */
    private $wrongAnswers;

    public function __construct()
    {
        $this->wrongAnswers = new ArrayCollection();
    }

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
     * Set quizId
     *
     * @param integer $quizId
     *
     * @return QuizQuestion
     */
    public function setQuizId($quizId)
    {
        $this->quizId = $quizId;

        return $this;
    }

    /**
     * Get quizId
     *
     * @return int
     */
    public function getQuizId()
    {
        return $this->quizId;
    }

    /**
     * Set isAnswered
     *
     * @param boolean $isAnswered
     *
     * @return QuizQuestion
     */
    public function setIsAnswered($isAnswered)
    {
        $this->isAnswered = $isAnswered;

        return $this;
    }

    /**
     * Get isAnswered
     *
     * @return bool
     */
    public function getIsAnswered()
    {
        return $this->isAnswered;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return QuizQuestion
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set random type
     *
     * @return QuizQuestion
     */
    public function setRandomType()
    {
        $this->type = self::TYPE_MAP[array_rand(self::TYPE_MAP)];

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set questionWordId
     *
     * @param integer $questionWordId
     *
     * @return QuizQuestion
     */
    public function setQuestionWordId($questionWordId)
    {
        $this->questionWordId = $questionWordId;

        return $this;
    }

    /**
     * Get questionWordId
     *
     * @return int
     */
    public function getQuestionWordId()
    {
        return $this->questionWordId;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return QuizQuestion
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return QuizQuestion
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
    // Quiz relation
    // ---------------------------------------------------------------------------------------------------------

    public function getQuiz()
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    // ---------------------------------------------------------------------------------------------------------
    // Wrong answers relation
    // ---------------------------------------------------------------------------------------------------------

    public function getWrongAnswers()
    {
        return $this->wrongAnswers;
    }

    public function addWrongAnswer(WrongAnswer $wrongAnswer)
    {
        if (!$this->wrongAnswers->contains($wrongAnswer)) {
            $this->wrongAnswers->add($wrongAnswer);
            $wrongAnswer->setQuestion($this);
        }

        return $this;
    }
}

