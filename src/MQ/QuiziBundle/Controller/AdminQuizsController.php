<?php

namespace MQ\QuiziBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MQ\QuiziBundle\Entity\Question;
use MQ\QuiziBundle\Entity\Quiz;
use MQ\QuiziBundle\Entity\Reponse;

class AdminQuizsController extends Controller
{

    // Liste des quizs d'un user
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


    // Ajout d'un quiz
    public function ajoutAction(Request $request){

        // Si on est du role Admin, on est redirigé
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->redirect($this->generateUrl("mq_quizi_admin_quizs"));
        }




        // Creation formulaire
        $data = array();
        $form = $this->createFormBuilder($data);

        $form = $form->add('nomQuiz','text');
        $form = $form->add('affichageFinal', 'choice', array(
            'choices' => array('1' => 'Score', '2' => 'Score + Résultat par question','3' => 'Score + Résultat par question + Bonne réponse'),
            'multiple' => false, 'expanded' => true
        ));

        $form = $form->add('nomQuestion','text');
        $form = $form->add('rep1','text');
        $form = $form->add('rep2','text');
        $form = $form->add('rep3','text', array('required' => false));
        $form = $form->add('rep4','text', array('required' => false));

        $form = $form->add('reponseCorrect', 'choice', array(
            'choices' => array('1' => 'Réponse 1', '2' => 'Réponse 2','3' => 'Réponse 3', '4' => 'Réponse 4'),
            'multiple' => false, 'expanded' => true
        ));

        $form = $form->add('btnCreer', 'submit', array('label' => 'Créer le Quiz', 'attr' => array('class' => 'btn waves-effect waves-light')));
        $form = $form->getForm();




        // Résultat Formulaire
        if($request->isMethod('POST')){

            $form->handleRequest($request);

            if($form->isValid()){

                $data = $form->getData();

                // Si les champs obligatoire ne sont pas vide
                if($data['nomQuiz'] != null && $data['nomQuestion'] != null && $data['rep1'] != null
                    && $data['rep2'] != null && $data['reponseCorrect'] != null && $data['affichageFinal'] != null){


                    // Si une case de réponse correct est cochée et cette réponse est vide
                    if(($data['rep4'] == null && $data['reponseCorrect'] == 4) ||
                        ($data['rep3'] == null && $data['reponseCorrect'] == 3) ){

                        return $this->render('MQQuiziBundle:AdminQuizs:adminAddQuizs.html.twig', array('form' => $form->createView(),'error' => 'Vous avez coché une réponse correcte qui est vide'));

                    }else{


                        // On ajoute la question et on redemande le formulaire
                        if($form->get('btnCreer')->isClicked()){


                            $em = $this->getDoctrine()->getManager();

                            $quiz = new Quiz();
                            $quiz->setTitreQuiz($data['nomQuiz']);
                            $quiz->setAffichageFinalQuiz($data['affichageFinal']);
                            date_default_timezone_set('UTC');
                            $date = date('d-m-Y');
                            $quiz->setDateCreationQuiz(new \DateTime($date));
                            $quiz->setUser($this->get('security.context')->getToken()->getUser());

                            // Ajout du quiz dans la BDD
                            $em->persist($quiz);
                            $em->flush();


                            $question = new Question();
                            $question->setTitreQuestion($data['nomQuestion']);
                            $question->setQuiz($quiz);


                            $reponse1 = new Reponse();
                            $reponse1->setTitreReponse($data['rep1']);

                            $reponse2 = new Reponse();
                            $reponse2->setTitreReponse($data['rep2']);

                            if($data['reponseCorrect'] == 1)
                                $reponse1->setBonneReponse(1);
                            else
                                $reponse1->setBonneReponse(0);
                            if($data['reponseCorrect'] == 2)
                                $reponse2->setBonneReponse(1);
                            else
                                $reponse2->setBonneReponse(0);

                            $question->addReponse($reponse1);
                            $question->addReponse($reponse2);

                            $em->persist($reponse1);
                            $em->persist($reponse2);

                            if($data['rep3'] != null){
                                $reponse3 = new Reponse();
                                $reponse3->setTitreReponse($data['rep3']);
                                if($data['reponseCorrect'] == 3)
                                    $reponse3->setBonneReponse(1);
                                else
                                    $reponse3->setBonneReponse(0);
                                $question->addReponse($reponse3);
                                $em->persist($reponse3);
                            }

                            if($data['rep4'] != null){
                                $reponse4 = new Reponse();
                                $reponse4->setTitreReponse($data['rep4']);
                                if($data['reponseCorrect'] == 4)
                                    $reponse4->setBonneReponse(1);
                                else
                                    $reponse4->setBonneReponse(0);
                                $question->addReponse($reponse4);
                                $em->persist($reponse4);
                            }

                            // Ajout de la question dans la BDD
                            $em->persist($question);
                            $em->flush();

                            return $this->redirect($this->generateUrl('mq_quizi_ajout_questions_quizs',array('idQuiz' => $quiz->getId())));

                        }


                    }


                }else{

                    return $this->render('MQQuiziBundle:AdminQuizs:adminAddQuizs.html.twig', array('form' => $form->createView(),'error' => 'Certains champs sont vides'));

                }


            }else{

                return $this->render('MQQuiziBundle:AdminQuizs:adminAddQuizs.html.twig', array('form' => $form->createView(),'error' => 'Formulaire non valide'));

            }

        }

        return $this->render('MQQuiziBundle:AdminQuizs:adminAddQuizs.html.twig', array('form' => $form->createView()));

    }


    public function ajoutQuestionsAction($idQuiz){

        // A développer

    }


}

