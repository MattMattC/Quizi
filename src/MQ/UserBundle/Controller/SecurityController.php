<?php
// src/OC/UserBundle/Controller/SecurityController.php;

namespace MQ\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\SecurityContext;
use MQ\UserBundle\Entity\User;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('mq_quizi_quizs');
        }
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('MQQuiziBundle:Default:index.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error_login' => "Nom d'utilisateur ou mot de passe incorrect",
        ));
    }

    /*
     *  Inscription utilisateur
     */
    public function inscriptionAction(Request $request){

        // Récupération des données envoyées par le formulaire d'inscription
        $request   = $this->container->get('request_stack')->getCurrentRequest();

        $username  = $request->request->get('username');
        $password  = $request->request->get('password');
        $password2 = $request->request->get('password2');
        $mail      = $request->request->get('mail');

        // Récupérations des users de la base de données
        // pour comparer l'existant
        $repository = $this->getDoctrine()->getRepository('MQUserBundle:User');
        $users      = $repository->findAll();

        $userDejaUtilise   = false;
        $mailDelaUtilise   = false;

        foreach ( $users as $userCourant ) {
            if ( $userCourant->getUsername() == $username ) {
                $userDejaUtilise=true;
            }
            if ( $mail == $userCourant->getMail() ) {
                $mailDelaUtilise = true;
            }
        }

        // On vérifie si le login n'est pas déjà utilisé
        if ( $userDejaUtilise ) {

            return $this->render('MQQuiziBundle:Default:index.html.twig', array(
                'error_inscription' => 'Utilisateur déjà utilisé'
            ));
        } else {

            // On vérifie que les mots de passes sont les même
            if ( $password != $password2 ) {
                return $this->render('MQQuiziBundle:Default:index.html.twig', array(
                    'error_inscription' => 'Mots de passe différents'
                ));
            }else{

                // On vérifie le format du mail
                if ( !filter_var( $mail, FILTER_VALIDATE_EMAIL ) ) {

                    return $this->render("MQQuiziBundle:Default:index.html.twig", array(
                        'error_inscription' => 'Format du mail incorrect : ****@***.**'
                    ));

                }else{

                    // On vérifie le mail n'est pas déjà utilisé
                    if ( $mailDelaUtilise ) {

                        return $this->render("MQQuiziBundle:Default:index.html.twig", array(
                            'error_inscription' => 'Mail déjà utilisé'
                        ));

                    } else {

                        // Création de l'utilisateur
                        $user = new User();

                        // Attribution des attributs
                        $user->setRoles(array('ROLE_USER'));
                        $user->setUsername($username);
                        $user->setMail($mail);

                        // Encodage du mot de passe
                        $encoder = $this->container->get('security.password_encoder');
                        $encoded = $encoder->encodePassword($user, $password);
                        $user->setPassword($encoded);

                        // Enregistrement sur la BDD
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($user);
                        $em->flush();

                        if ( !$user ) {

                            throw new UsernameNotFoundException("User not found");
                        } else {

                            $token = new UsernamePasswordToken($user, null, "main", $user->getRoles());
                            $this->get("security.context")->setToken($token);

                            // Log le user inscrit
                            $request = $this->get("request");
                            $event = new InteractiveLoginEvent($request, $token);
                            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
                        }

                        return $this->redirect($this->generateUrl('mq_quizi_homepage'));
                    }
                }

            }
            return $this->render("MQQuiziBundle:Default:index.html.twig");
        }
    }


    /*
     *  Affichage des utilisateurs
     *
     */
    public function GestionUserAction(){
        if ( $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if( $this->getUser()->getRoles()[0] != 'ROLE_ADMIN'){
                return $this->redirectToRoute('mq_quizi_homepage');
            }else{
                $repository = $this->getDoctrine()
                    ->getRepository('MQUserBundle:User');
                $users = $repository->findAll();

                return $this->render('MQUserBundle:Security:gestion.html.twig', array('users' => $users));
            }
        }else {
            return $this->redirectToRoute('mq_quizi_homepage');
        }
    }

    /*
     * Suppresion des Utilisateurs
     */

    public function deleteUserAction($id){

        if ( $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if( $this->getUser()->getRoles()[0] != 'ROLE_ADMIN'){
                return $this->redirectToRoute('mq_quizi_quizs');
            }else{

                // vérification existance de l'utilisateur
                $em = $this->getDoctrine()->getManager();
                $user= $em->getRepository('MQUserBundle:User')->find($id);
                if (!$user) {
                    throw $this->createNotFoundException(
                        'Pas d\'utilisateur trouvé :' . $id
                    );
                }else{
                    if($user->getRoles() == 'ROLE_ADMIN'){
                        throw $this->createNotFoundException(
                            'Impossible de supprimée l\'utilisateur :' . $id . ' car il est ADMIN'
                        );
                    }else{
                        $em->remove($user);
                        $em->flush();
                    }
                }

                return $this->redirect($this->generateUrl("gestion_user"));
            }
        }else{
            return $this->redirectToRoute('mq_quizi_quizs');
        }
    }




}

?>