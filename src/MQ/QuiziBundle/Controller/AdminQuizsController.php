<?php

namespace MQ\QuiziBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminQuizsController extends Controller
{

    public function indexAction()
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MQQuiziBundle:Quiz')
        ;

        $listQuizs = $repository->findAll(); // FAIRE METHODE FIND BY

        return $this->render('MQQuiziBundle:AdminQuizs:adminQuizs.html.twig',array('listQuizs' => $listQuizs));
    }


}

