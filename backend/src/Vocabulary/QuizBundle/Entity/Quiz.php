<?php

namespace Vocabulary\QuizBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Quiz
 */
class Quiz
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $score;

    /**
     * @var int
     */
    private $currentFailsNumber;

    /**
     * @var int
     */
    private $failsNumber;

    /**
     * @var int
     */
    private $currentQuestionNumber;

    /**
     * @var int
     */
    private $questionsNumber;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    // ---------------------------------------------------------------------------------------------------------
    // Virtual fields for /answer action
    // ---------------------------------------------------------------------------------------------------------

    /**
     * @var boolean
     */
    private $isAnswerRight = false;

    // ---------------------------------------------------------------------------------------------------------
    // Virtual fields for /current-question action
    // ---------------------------------------------------------------------------------------------------------

    /**
     * @var array
     */
    private $currentQuestion;

    /**
     * @var array
     */
    private $possibleAnswersForCurrentQuestion;

    // ---------------------------------------------------------------------------------------------------------
    // Relations
    // ---------------------------------------------------------------------------------------------------------

    /**
     * @var QuizQuestion[]
     */
    private $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Quiz
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return Quiz
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set currentFailsNumber
     *
     * @param integer $currentFailsNumber
     *
     * @return Quiz
     */
    public function setCurrentFailsNumber($currentFailsNumber)
    {
        $this->currentFailsNumber = $currentFailsNumber;

        return $this;
    }

    /**
     * Get currentFailsNumber
     *
     * @return int
     */
    public function getCurrentFailsNumber()
    {
        return $this->currentFailsNumber;
    }

    /**
     * Set failsNumber
     *
     * @param integer $failsNumber
     *
     * @return Quiz
     */
    public function setFailsNumber($failsNumber)
    {
        $this->failsNumber = $failsNumber;

        return $this;
    }

    /**
     * Get failsNumber
     *
     * @return int
     */
    public function getFailsNumber()
    {
        return $this->failsNumber;
    }

    /**
     * Set currentQuestionNumber
     *
     * @param integer $currentQuestionNumber
     *
     * @return Quiz
     */
    public function setCurrentQuestionNumber($currentQuestionNumber)
    {
        $this->currentQuestionNumber = $currentQuestionNumber;

        return $this;
    }

    /**
     * Get currentQuestionNumber
     *
     * @return int
     */
    public function getCurrentQuestionNumber()
    {
        return $this->currentQuestionNumber;
    }

    /**
     * Set questionsNumber
     *
     * @param integer $questionsNumber
     *
     * @return Quiz
     */
    public function setQuestionsNumber($questionsNumber)
    {
        $this->questionsNumber = $questionsNumber;

        return $this;
    }

    /**
     * Get questionsNumber
     *
     * @return int
     */
    public function getQuestionsNumber()
    {
        return $this->questionsNumber;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Quiz
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
     * @return Quiz
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

    public function getIsFinished()
    {
        return $this->failsNumber <= $this->currentFailsNumber || $this->questionsNumber <= $this->currentQuestionNumber;
    }

    // ---------------------------------------------------------------------------------------------------------
    // Virtual getter/setter for /answer action
    // ---------------------------------------------------------------------------------------------------------

    /**
     * Set isAnswerRight
     *
     * @param bool $isAnswerRight
     *
     * @return Quiz
     */
    public function setIsAnswerRight($isAnswerRight)
    {
        $this->isAnswerRight = $isAnswerRight;

        return $this;
    }

    /**
     * Get isAnswerRight
     *
     * @return bool
     */
    public function getIsAnswerRight()
    {
        return $this->isAnswerRight;
    }

    // ---------------------------------------------------------------------------------------------------------
    // Virtual getter/setter for /current-question action
    // ---------------------------------------------------------------------------------------------------------

    public function setCurrentQuestion($currentQuestion)
    {
        $this->currentQuestion = $currentQuestion;
    }

    public function getCurrentQuestion()
    {
        return $this->currentQuestion;
    }

    public function setPossibleAnswers($possibleAnswers)
    {
        $this->possibleAnswersForCurrentQuestion = $possibleAnswers;
    }

    public function getPossibleAnswers()
    {
        return $this->possibleAnswersForCurrentQuestion;
    }

    // ---------------------------------------------------------------------------------------------------------
    // Questions relation
    // ---------------------------------------------------------------------------------------------------------

    public function getQuestions()
    {
        return $this->questions;
    }

    public function getLastQuestion()
    {
        $criteria = Criteria::create();
        $criteria->orderBy(['id' => Criteria::DESC])->getFirstResult();

        return $this->questions->matching($criteria);
    }

    public function hasSameQuestion(QuizQuestion $_question)
    {
        foreach ($this->questions as $question) {
            if ($question->getType() === $_question->getType()
                && $question->getQuestionWordId() === $_question->getQuestionWordId()
            ) {
                return true;
            }
        }

        return false;
    }

    public function addQuestion(QuizQuestion $question)
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuiz($this);
        }

        return $this;
    }
}

