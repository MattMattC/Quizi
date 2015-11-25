<?php
/**
 * Created by PhpStorm.
 * User: quentinbrodier
 * Date: 23/11/2015
 * Time: 15:51
 */

namespace MQ\QuiziBundle\Entity;


class ResultatUtilisateurQuestion
{

    private $idQuestion;

    private $titreQuestion;

    private $idReponseUtilisateur;

    private $idReponseCorrect;

    private $reponseUtilisateur;

    private $reponseCorrect;

    public function __construct($id,$titre,$idRepU) {

        $this->idQuestion = $id;
        $this->titreQuestion = $titre;
        $this->idReponseUtilisateur = $idRepU;

    }

    public function getIdQuestion(){

        return $this->idQuestion;

    }

    public function getTitreQuestion(){

        return $this->titreQuestion;

    }

    public function getIdReponseUtilisateur(){

        return $this->idReponseUtilisateur;

    }

    public function getIdReponseCorrect(){

        return $this->idReponseCorrect;

    }

    public function getReponseUtilisateur(){

        return $this->reponseUtilisateur;

    }

    public function getReponseCorrect(){

        return $this->reponseCorrect;

    }

    public function setIdQuestion($id){

        $this->idQuestion = $id;

    }

    public function setTitreQuestion($titre){

        $this->titreQuestion = $titre;

    }

    public function setIdReponseUtilisateur($idRepU){

        $this->idReponseUtilisateur = $idRepU;

    }

    public function setIdReponseCorrect($idRepC){

        $this->idReponseCorrect = $idRepC;

    }


    public function setReponseUtilisateur($repU){

        $this->reponseUtilisateur = $repU;

    }

    public function setReponseCorrect($repC){

        $this->reponseCorrect = $repC;

    }


}