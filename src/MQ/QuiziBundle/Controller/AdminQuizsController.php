<?php

namespace MQ\QuiziBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MQ\QuiziBundle\Entity\Question;
use MQ\QuiziBundle\Entity\Quiz;
use MQ\QuiziBundle\Entity\Reponse;

class AdminQuizsController extends Controller
{

    /*
     *
     * Cette fonction permet de lister les quizs dans l'espace Administration
     *
     */
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


    /*
     *
     * Cette fonction permet d'ajouter un quiz
     *
     */
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

                            return $this->redirect($this->generateUrl('mq_quizi_modif_quizs',array('idQuiz' => $quiz->getId())));

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


    /*
     *
     * Cette fonction permet de supprimer un quiz
     *
     */
    public function supprimerQuizAction($idQuiz) {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MQQuiziBundle:Quiz')
        ;

        // On récupère l'entité correspondante à l'id $id
        $quiz = $repository->find($idQuiz);

        if (null === $quiz) {
            throw new NotFoundHttpException("Le quiz id = ".$idQuiz." n'existe pas.");
        }

        if( ($quiz->getUser() != $this->get('security.context')->getToken()->getUser()) &&
            !($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))  ){
            throw new NotFoundHttpException("Vous essayez de supprimer une question d'un quiz qui n'est pas le votre.");
        }


        // On récupère la liste des questions de ce quiz
        $listQuestions = $this->getDoctrine()->getManager()
            ->getRepository('MQQuiziBundle:Question')
            ->findBy(array('quiz' => $quiz))
        ;

        $em = $this->getDoctrine()->getManager();
        foreach($listQuestions as $question){

            foreach($question->getReponses() as $rep)
                $em->remove($rep);

            $em->remove($question);
        }


        $em->remove($quiz);
        $em->flush();

        return $this->redirect($this->generateUrl('mq_quizi_admin_quizs'));


    }



    /*
     *
     * Cette fonction permet de modifier un quiz et son contenu
     *
     */
    public function modifQuizAction($idQuiz, Request $request){

        // Si on est du role Admin, on est redirigé
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->redirect($this->generateUrl("mq_quizi_admin_quizs"));
        }


        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MQQuiziBundle:Quiz')
        ;

        // On récupère l'entité correspondante à l'id $id
        $quiz = $repository->find($idQuiz);

        if (null === $quiz) {
            throw new NotFoundHttpException("Le quiz id = ".$idQuiz." n'existe pas.");
        }

        if($quiz->getUser() != $this->get('security.context')->getToken()->getUser()){
            throw new NotFoundHttpException("Vous essayez de modifier un quiz qui n'est pas le votre.");
        }


        // On récupère la liste des questions de ce quiz
        $listQuestions = $this->getDoctrine()->getManager()
            ->getRepository('MQQuiziBundle:Question')
            ->findBy(array('quiz' => $quiz))
        ;




        // Creation formulaire
        $data = array();
        $form = $this->createFormBuilder($data);

        $form = $form->add('nomQuiz','text',array('data' => $quiz->getTitreQuiz()));
        $form = $form->add('affichageFinal', 'choice', array(
            'choices' => array('1' => 'Score', '2' => 'Score + Résultat par question','3' => 'Score + Résultat par question + Bonne réponse'),
            'multiple' => false, 'expanded' => true, 'data' => $quiz->getAffichageFinalQuiz()
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

        $form = $form->add('btnModifInfoQuiz', 'submit', array('label' => 'Modifier', 'attr' => array('class' => 'btn waves-effect waves-light')));
        $form = $form->add('btnAddQuestion', 'submit', array('label' => 'Ajouter une question', 'attr' => array('class' => 'btn waves-effect waves-light')));
        $form = $form->getForm();





        // Résultat Formulaire
        if($request->isMethod('POST')){

            $form->handleRequest($request);

            if($form->isValid()){


                $data = $form->getData();

                // Modification des infos du quiz
                if ($form->get('btnModifInfoQuiz')->isClicked()) {


                    // Si les champs ne sont pas vide
                    if($data['nomQuiz'] != null && $data['affichageFinal'] != null){

                        $em = $this->getDoctrine()->getManager();

                        $quiz->setTitreQuiz($data['nomQuiz']);
                        $quiz->setAffichageFinalQuiz($data['affichageFinal']);

                        // Modification du quiz dans la BDD
                        $em->persist($quiz);
                        $em->flush();

                    }else{

                        return $this->render('MQQuiziBundle:AdminQuizs:adminModifQuizs.html.twig', array('listQuestions' => $listQuestions, 'quiz' => $quiz, 'form' => $form->createView(),'error' => 'Modification non effectuée : certains champs étaient vides'));

                    }

                }



                // Ajout d'une question pour un quiz
                if ($form->get('btnAddQuestion')->isClicked()) {

                    // Si les champs obligatoire ne sont pas vide
                    if($data['nomQuestion'] != null && $data['rep1'] != null
                        && $data['rep2'] != null && $data['reponseCorrect'] != null){


                        // Si une case de réponse correct est cochée et cette réponse est vide
                        if(($data['rep4'] == null && $data['reponseCorrect'] == 4) ||
                            ($data['rep3'] == null && $data['reponseCorrect'] == 3) ){

                            return $this->render('MQQuiziBundle:AdminQuizs:adminModifQuizs.html.twig', array('listQuestions' => $listQuestions, 'quiz' => $quiz, 'form' => $form->createView(),'error' => 'Question non ajoutée : vous avez coché une réponse correcte qui est vide'));

                        }else{

                            $em = $this->getDoctrine()->getManager();

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

                            return $this->redirect($this->generateUrl('mq_quizi_modif_quizs',array('idQuiz' => $quiz->getId())));

                        }

                    }else{

                        return $this->render('MQQuiziBundle:AdminQuizs:adminModifQuizs.html.twig', array('listQuestions' => $listQuestions, 'quiz' => $quiz, 'form' => $form->createView(),'error' => 'Question non ajoutée : certains champs étaient vides'));

                    }

                }


            }else{

                return $this->render('MQQuiziBundle:AdminQuizs:adminModifQuizs.html.twig', array('listQuestions' => $listQuestions, 'quiz' => $quiz, 'form' => $form->createView(),'error' => 'Formulaire non valide'));

            }

        }


        return $this->render('MQQuiziBundle:AdminQuizs:adminModifQuizs.html.twig', array('listQuestions' => $listQuestions, 'quiz' => $quiz, 'form' => $form->createView()));

    }


    /*
     *
     * Cette fonction permet de modifier le contenu d'une question
     *
     */
    public function modifierQuestionsAction($idQuiz, $idQuestion, Request $request) {

        // Si on est du role Admin, on est redirigé
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->redirect($this->generateUrl("mq_quizi_admin_quizs"));
        }

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MQQuiziBundle:Quiz')
        ;

        // On récupère l'entité correspondante à l'id $id
        $quiz = $repository->find($idQuiz);

        if (null === $quiz) {
            throw new NotFoundHttpException("Le quiz id = ".$idQuiz." n'existe pas.");
        }

        if($quiz->getUser() != $this->get('security.context')->getToken()->getUser()){
            throw new NotFoundHttpException("Vous essayez de modifier une question d'un quiz qui n'est pas le votre.");
        }


        // On récupère la liste des questions de ce quiz
        $listQuestions = $this->getDoctrine()->getManager()
            ->getRepository('MQQuiziBundle:Question')
            ->findBy(array('quiz' => $quiz))
        ;

        $repository2 = $this->getDoctrine()
            ->getManager()
            ->getRepository('MQQuiziBundle:Question')
        ;

        $question = $repository2->find($idQuestion);

        if (null === $question) {
            throw new NotFoundHttpException("La question = ".$idQuestion." n'existe pas");
        }

        $bool = false;
        foreach($listQuestions as $q){

            if($q == $question)
                $bool = true;

        }


        // Si la question appartient bien au quiz actuel, on supprime
        if($bool == true){





            // Creation formulaire
            $data = array();
            $form = $this->createFormBuilder($data);

            $form = $form->add('nomQuestion','text', array('data' => $question->getTitreQuestion()));
            $form = $form->add('rep1','text', array('data' => $question->getReponses()[0]->getTitreReponse()));
            $form = $form->add('rep2','text', array('data' => $question->getReponses()[1]->getTitreReponse()));

            if(sizeof($question->getReponses()) == 4){
                $form = $form->add('rep3','text', array('data' => $question->getReponses()[2]->getTitreReponse(), 'required' => false));
                $form = $form->add('rep4','text', array('data' => $question->getReponses()[3]->getTitreReponse(), 'required' => false));
            }

            if(sizeof($question->getReponses()) == 3){
                $form = $form->add('rep3','text', array('data' => $question->getReponses()[2]->getTitreReponse(), 'required' => false));
                $form = $form->add('rep4','text', array('required' => false));
            }

            if(sizeof($question->getReponses()) == 2){
                $form = $form->add('rep3','text', array('required' => false));
                $form = $form->add('rep4','text', array('required' => false));
            }

            // Quel est la bonne réponse ?
            for($i = 0 ; $i < sizeof($question->getReponses()) ; $i++){
                if($question->getReponses()[$i]->getBonneReponse() == 1)
                    $bonneReponse = $i+1;
            }

            $form = $form->add('reponseCorrect', 'choice', array(
                'choices' => array('1' => 'Réponse 1', '2' => 'Réponse 2','3' => 'Réponse 3', '4' => 'Réponse 4'),
                'multiple' => false, 'expanded' => true, 'data' => $bonneReponse
            ));

            $form = $form->add('btnModifQuestion', 'submit', array('label' => 'Modifier', 'attr' => array('class' => 'btn waves-effect waves-light')));
            $form = $form->getForm();




            // Résultat Formulaire
            if($request->isMethod('POST')){

                $form->handleRequest($request);

                if($form->isValid()){


                    $data = $form->getData();


                    // Ajout d'une question pour un quiz
                    if ($form->get('btnModifQuestion')->isClicked()) {

                        // Si les champs obligatoire ne sont pas vide
                        if($data['nomQuestion'] != null && $data['rep1'] != null
                            && $data['rep2'] != null && $data['reponseCorrect'] != null){


                            // Si une case de réponse correct est cochée et cette réponse est vide
                            if(($data['rep4'] == null && $data['reponseCorrect'] == 4) ||
                                ($data['rep3'] == null && $data['reponseCorrect'] == 3) ){

                                return $this->render('MQQuiziBundle:AdminQuizs:adminModifQuestion.html.twig', array('form' => $form->createView(),'error' => 'Question non modifiée : vous avez coché une réponse correcte qui est vide'));

                            }else{

                                $em = $this->getDoctrine()->getManager();

                                foreach($question->getReponses() as $rep)
                                    $em->remove($rep);
                                $em->remove($question);
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

                                return $this->redirect($this->generateUrl('mq_quizi_modif_quizs',array('idQuiz' => $quiz->getId())));

                            }

                        }else{

                            return $this->render('MQQuiziBundle:AdminQuizs:adminModifQuestion.html.twig', array('form' => $form->createView(),'error' => 'Question non modifiée : certains champs étaient vides'));

                        }

                    }


                }else{

                    return $this->render('MQQuiziBundle:AdminQuizs:adminModifQuizs.html.twig', array('listQuestions' => $listQuestions, 'quiz' => $quiz, 'form' => $form->createView(),'error' => 'Formulaire non valide'));

                }

            }




            return $this->render('MQQuiziBundle:AdminQuizs:adminModifQuestion.html.twig', array('form' => $form->createView()));




        }else{
            throw new NotFoundHttpException("La question = ".$idQuestion." n'appartient pas au quiz actuel.");
        }


    }



    /*
     *
     * Cette fonction permet de supprimer une question dans un quiz
     *
     */
    public function supprimerQuestionsAction($idQuiz, $idQuestion) {

        // Si on est du role Admin, on est redirigé
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->redirect($this->generateUrl("mq_quizi_admin_quizs"));
        }

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MQQuiziBundle:Quiz')
        ;

        // On récupère l'entité correspondante à l'id $id
        $quiz = $repository->find($idQuiz);

        if (null === $quiz) {
            throw new NotFoundHttpException("Le quiz id = ".$idQuiz." n'existe pas.");
        }

        if($quiz->getUser() != $this->get('security.context')->getToken()->getUser()){
            throw new NotFoundHttpException("Vous essayez de supprimer une question d'un quiz qui n'est pas le votre.");
        }


        // On récupère la liste des questions de ce quiz
        $listQuestions = $this->getDoctrine()->getManager()
            ->getRepository('MQQuiziBundle:Question')
            ->findBy(array('quiz' => $quiz))
        ;

        $repository2 = $this->getDoctrine()
            ->getManager()
            ->getRepository('MQQuiziBundle:Question')
        ;

        $question = $repository2->find($idQuestion);

        if (null === $question) {
            throw new NotFoundHttpException("La question = ".$idQuestion." n'existe pas");
        }

        $bool = false;
        foreach($listQuestions as $q){

            if($q == $question)
                $bool = true;

        }


        // Si la question appartient bien au quiz actuel, on supprime
        if($bool == true){

            // Si la liste des questions est différente de 1, on peut supprimer
            if(sizeof($listQuestions) != 1){


                $em = $this->getDoctrine()->getManager();

                foreach($question->getReponses() as $rep)
                    $em->remove($rep);
                $em->remove($question);
                $em->flush();

            }else{

                return $this->redirect($this->generateUrl('mq_quizi_modif_quizs',array('idQuiz' => $quiz->getId())));

            }


        }else{
            throw new NotFoundHttpException("La question = ".$idQuestion." n'appartient pas au quiz actuel.");
        }


        return $this->redirect($this->generateUrl('mq_quizi_modif_quizs',array('idQuiz' => $quiz->getId())));


    }


}

