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

        // Le service authentication_utils permet de récupérer le nom d'utilisateur
        // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
        // (mauvais mot de passe par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('MQUserBundle:Security:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    public function inscriptionAction(Request $request){

        $request   = $this->container->get('request_stack')->getCurrentRequest();
        $username  = $request->request->get('username');
        $password  = $request->request->get('password');

        $mail     = $request->request->get('mail');

        $repository = $this->getDoctrine()->getRepository('MQUserBundle:User');
        $users = $repository->findAll();

        $userDejaUtilise = false;
        $mailDelaUtilise = false;

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
            return $this->redirect($this->generateUrl('mq_quizi_homepage', array('error' => 'Utilisateur déjà utilisé')));
        }else {
            // On vérifie le mail n'est pas déjà utilisé
            if ($mailDelaUtilise) {
                return $this->redirect($this->generateUrl('mq_quizi_homepage', array('error' => 'Mail déjà utilisé')));
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


                if (!$user) {
                    throw new UsernameNotFoundException("User not found");
                } else {
                    $token = new UsernamePasswordToken($user, null, "main", $user->getRoles());
                    $this->get("security.context")->setToken($token); //now the user is logged in

                    //now dispatch the login event
                    $request = $this->get("request");
                    $event = new InteractiveLoginEvent($request, $token);
                    $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
                }

                $form = $this->createFormBuilder($user, array('csrf_protection' => false))
                    ->setAction($this->generateUrl('login'))
                    ->setMethod('POST')
                    ->add('username', 'text', array('data' => $username))
                    ->add('password', 'password', array('data' => $password))
                    ->getForm();
               // $form->submit($request->request->get($form->getName()));

               return $this->redirect($this->generateUrl('mq_quizi_homepage'));
            }
        }


        return $this->render('MQUserBundle:Security:inscription.html.twig', array(
            'form' => $form->createView(),
        ));

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
                    $em->remove($user);
                    $em->flush();
                }

                return $this->redirect($this->generateUrl("gestion_user"));
            }
        }else{
            return $this->redirectToRoute('mq_quizi_quizs');
        }
    }




}

?>