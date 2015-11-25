<?php

namespace MQ\QuiziBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponse
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MQ\QuiziBundle\Entity\ReponseRepository")
 */
class Reponse
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
     * @ORM\Column(name="titreReponse", type="text")
     */
    private $titreReponse;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bonneReponse", type="boolean")
     */
    private $bonneReponse;


    /**
     * @ORM\ManyToOne(targetEntity="MQ\QuiziBundle\Entity\Question",inversedBy="reponses",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;


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
     * Set titreReponse
     *
     * @param string $titreReponse
     *
     * @return Reponse
     */
    public function setTitreReponse($titreReponse)
    {
        $this->titreReponse = $titreReponse;

        return $this;
    }

    /**
     * Get titreReponse
     *
     * @return string
     */
    public function getTitreReponse()
    {
        return $this->titreReponse;
    }

    /**
     * Set bonneReponse
     *
     * @param boolean $bonneReponse
     *
     * @return Reponse
     */
    public function setBonneReponse($bonneReponse)
    {
        $this->bonneReponse = $bonneReponse;

        return $this;
    }

    /**
     * Get bonneReponse
     *
     * @return boolean
     */
    public function getBonneReponse()
    {
        return $this->bonneReponse;
    }

    /**
     * Set question
     *
     * @param \MQ\QuiziBundle\Entity\Question $question
     *
     * @return Reponse
     */
    public function setQuestion(\MQ\QuiziBundle\Entity\Question $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \MQ\QuiziBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
