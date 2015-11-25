<?php

namespace MQ\QuiziBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MQ\QuiziBundle\Entity\QuestionRepository")
 */
class Question
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titreQuestion", type="text")
     */
    private $titreQuestion;


    /**
     * @ORM\ManyToOne(targetEntity="MQ\QuiziBundle\Entity\Quiz")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;


    /**
     * @ORM\OneToMany(targetEntity="MQ\QuiziBundle\Entity\Reponse", mappedBy="question")
     */
    private $reponses;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titreQuestion
     *
     * @param string $titreQuestion
     *
     * @return Question
     */
    public function setTitreQuestion($titreQuestion)
    {
        $this->titreQuestion = $titreQuestion;

        return $this;
    }

    /**
     * Get titreQuestion
     *
     * @return string
     */
    public function getTitreQuestion()
    {
        return $this->titreQuestion;
    }

    /**
     * Set quiz
     *
     * @param \MQ\QuiziBundle\Entity\Quiz $quiz
     *
     * @return Question
     */
    public function setQuiz(\MQ\QuiziBundle\Entity\Quiz $quiz)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \MQ\QuiziBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reponses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reponse
     *
     * @param \MQ\QuiziBundle\Entity\Reponse $reponse
     *
     * @return Question
     */
    public function addReponse(\MQ\QuiziBundle\Entity\Reponse $reponse)
    {
        $this->reponses[] = $reponse;

        $reponse->setQuestion($this);

        return $this;
    }

    /**
     * Remove reponse
     *
     * @param \MQ\QuiziBundle\Entity\Reponse $reponse
     */
    public function removeReponse(\MQ\QuiziBundle\Entity\Reponse $reponse)
    {
        $this->reponses->removeElement($reponse);
    }

    /**
     * Get reponses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReponses()
    {
        return $this->reponses;
    }


}
