<?php

namespace MQ\QuiziBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MQ\QuiziBundle\Entity\QuizRepository")
 */
class Quiz
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
     * @ORM\Column(name="titreQuiz", type="string", length=100)
     */
    private $titreQuiz;

    /**
     * @var integer
     *
     * @ORM\Column(name="affichageFinalQuiz", type="integer")
     */
    private $affichageFinalQuiz;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreationQuiz", type="datetime")
     */
    private $dateCreationQuiz;

    /**
     * @ORM\ManyToOne(targetEntity="MQ\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;



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
     * Set titreQuiz
     *
     * @param string $titreQuiz
     *
     * @return Quiz
     */
    public function setTitreQuiz($titreQuiz)
    {
        $this->titreQuiz = $titreQuiz;

        return $this;
    }

    /**
     * Get titreQuiz
     *
     * @return string
     */
    public function getTitreQuiz()
    {
        return $this->titreQuiz;
    }

    /**
     * Set affichageFinalQuiz
     *
     * @param integer $affichageFinalQuiz
     *
     * @return Quiz
     */
    public function setAffichageFinalQuiz($affichageFinalQuiz)
    {
        $this->affichageFinalQuiz = $affichageFinalQuiz;

        return $this;
    }

    /**
     * Get affichageFinalQuiz
     *
     * @return integer
     */
    public function getAffichageFinalQuiz()
    {
        return $this->affichageFinalQuiz;
    }

    /**
     * Set dateCreationQuiz
     *
     * @param \DateTime $dateCreationQuiz
     *
     * @return Quiz
     */
    public function setDateCreationQuiz($dateCreationQuiz)
    {
        $this->dateCreationQuiz = $dateCreationQuiz;

        return $this;
    }

    /**
     * Get dateCreationQuiz
     *
     * @return \DateTime
     */
    public function getDateCreationQuiz()
    {
        return $this->dateCreationQuiz;
    }



    /**
     * Set user
     *
     * @param \MQ\UserBundle\Entity\User $user
     *
     * @return Quiz
     */
    public function setUser(\MQ\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \MQ\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
