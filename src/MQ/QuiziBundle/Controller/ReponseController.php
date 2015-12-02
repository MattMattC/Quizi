<?php

namespace MQ\QuiziBundle\Controller;

use MQ\QuiziBundle\Entity\ResultatUtilisateurQuestion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class ReponseController extends Controller
{

    public function repQuizAction($idQuiz)
    {

        $em = $this->getDoctrine()->getManager();
        $request = Request::createFromGlobals();

        // On récupère le quiz $idQuiz
        $quiz = $em
            ->getRepository('MQQuiziBundle:Quiz')
            ->find($idQuiz)
        ;

        if ( null === $quiz ) {
            throw new NotFoundHttpException("Le quiz id = ".$idQuiz." n'existe pas.");
        }

        // On récupère la liste des questions de ce quiz
        $listQuestions = $em
            ->getRepository('MQQuiziBundle:Question')
            ->findBy(array('quiz' => $quiz))
        ;
        // Creation formulaire
        $data = array();
        $form = $this->createFormBuilder($data);
        $tabReponseQuestion = array();
        // On parcours chaque questions
        for( $i = 1 ; $i <= sizeof( $listQuestions ) ; $i++ ) {

            $listeReponses = array();

            // On parcours chaque réponses pour chaque question
            $tailleReponse = sizeof($listQuestions[$i-1]->getReponses());
            $tabReponseQuestion[$i]=array();
            for($j = 0 ; $j < $tailleReponse ; $j++ ) {
                $reponse = $listQuestions[$i-1]->getReponses()[$j];
                $listeReponses[$reponse->getId()] = "reponse ".($j+1);
                array_push($tabReponseQuestion[$i], $reponse->getTitreReponse());

            }

            $form = $form->add('question' . $i, 'choice', array(
                    'choices' => $listeReponses,
                    'multiple' => false, 'expanded' => true, 'attr' => array(
                        'onclick' => 'checkQuestion(' . $i . ');'
                    )
                )
            );

        }

        $form = $form->add('save', 'submit', array(
                'label' => 'Valider', 'attr' => array(
                    'class' => 'btn waves-effect waves-light')
            )
        );
        $form = $form->getForm();


        // Si on à valider le formulaire !
        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            $data = $form->getData();
            // Construction du tableau de retour
            $tabResultats = array();

            $nbReponsesCorrect = 0;

            for($i = 0 ; $i < sizeof($listQuestions) ; $i++){

                $resQuestion = new ResultatUtilisateurQuestion(
                    $listQuestions[$i]->getId(),
                    $listQuestions[$i]->getTitreQuestion(),
                    $data['question'.($i+1)]);


                for($j = 0 ; $j < sizeof($listQuestions[$i]->getReponses()) ; $j++){

                    // On récupère le titre de la réponse selectionée
                    if($listQuestions[$i]->getReponses()[$j]->getId() == $data['question'.($i+1)]){
                        $resQuestion->setReponseUtilisateur($listQuestions[$i]->getReponses()[$j]->getTitreReponse());
                    }

                    if($listQuestions[$i]->getReponses()[$j]->getBonneReponse()){
                        $resQuestion->setIdReponseCorrect($listQuestions[$i]->getReponses()[$j]->getId());
                        $resQuestion->setReponseCorrect($listQuestions[$i]->getReponses()[$j]->getTitreReponse());

                        if($listQuestions[$i]->getReponses()[$j]->getId() == $data['question'.($i+1)]){
                            $nbReponsesCorrect++;
                        }

                    }

                }

                $tabResultats[$i] = $resQuestion;
            }


            return $this->render('MQQuiziBundle:Resultat:resultatQuizs.html.twig',
                array('tabResultats' => $tabResultats,
                    'quiz' => $quiz,
                    'nbReponsesCorrect' => $nbReponsesCorrect,
                    'nbQuestions' => sizeof($listQuestions),
                    'pourcentageRes' => $nbReponsesCorrect/sizeof($listQuestions)));


        }


        return $this->render('MQQuiziBundle:Reponse:repQuizs.html.twig',array('tabRepQue' => $tabReponseQuestion, 'quiz' => $quiz,'listQuestions' => $listQuestions, 'form' => $form->createView()));

    }

}

