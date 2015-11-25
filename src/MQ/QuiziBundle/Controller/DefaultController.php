<?php

namespace MQ\QuiziBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('MQQuiziBundle:Default:index.html.twig');
        }else{
            return $this->redirect($this->generateUrl("mq_quizi_quizs"));
        }

    }


}

