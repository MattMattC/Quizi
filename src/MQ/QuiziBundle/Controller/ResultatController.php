<?php

namespace MQ\QuiziBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ResultatController extends Controller
{

    public function resultatQuizAction()
    {

        $request = Request::createFromGlobals();

        //$value = $request->request->get('question1');


        $value = $request->request->get('question1');

        //var_dump($request);

        return $this->render('MQQuiziBundle:Resultat:resultatQuizs.html.twig',array('value' => $value));

    }

}

