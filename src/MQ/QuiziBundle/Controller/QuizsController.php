<?php

namespace MQ\QuiziBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuizsController extends Controller
{

    public function indexQuizAction()
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MQQuiziBundle:Quiz')
        ;

        $listQuizs = $repository->findAll();

        return $this->render('MQQuiziBundle:Quiz:quizs.html.twig',array('listQuizs' => $listQuizs));
    }

    public function viewQuizAction($idQuiz)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MQQuiziBundle:Quiz')
        ;

        // On récupère l'entité correspondante à l'id $id
        $quiz = $repository->find($idQuiz);

        // $advert est donc une instance de MQ\QuiziBundle\Entity\Quiz
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $quiz) {
            throw new NotFoundHttpException("Le quiz id = ".$idQuiz." n'existe pas.");
        }

        return $this->render('MQQuiziBundle:Quiz:viewQuizs.html.twig',array('quiz' => $quiz));

    }


}

