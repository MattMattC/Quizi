<?php

namespace MQ\QuiziBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminAddQuizsController extends Controller
{

    public function indexAction()
    {

        return $this->render('MQQuiziBundle:AdminQuizs:adminAddQuizs.html.twig');

    }


}

