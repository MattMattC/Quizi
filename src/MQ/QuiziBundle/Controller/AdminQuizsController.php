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


        // Si on est USER, on récupère la liste des quizs du user
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $listQuizs = $repository->findAll();
        }else{
            $listQuizs = $repository->findBy(array('user' => $this->get('security.context')->getToken()->getUser() ),null,null,0);
        }

        return $this->render('MQQuiziBundle:AdminQuizs:adminQuizs.html.twig',array('listQuizs' => $listQuizs));
    }


}

