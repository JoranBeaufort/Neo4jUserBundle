<?php
// src/AppBundle/Controller/SecurityController.php
namespace JoranBeaufort\Neo4jUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

use JoranBeaufort\Neo4jUserBundle\Form\PasswordResetType;
use JoranBeaufort\Neo4jUserBundle\Entity\User;

class SecurityController extends Controller
{

    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'Neo4jUserBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }
    
    public function passwordResetAction(Request $request)
    {
        $form = $this->createForm(passwordResetType::class);
        $form->handleRequest($request);
        $error = null;
        
        if ($form->isSubmitted() && $form->isValid()) {
                
            $em = $this->get('neo4j.graph_manager')->getClient();
            $email = $form->getData()['email'];

            $user=$em->getRepository(User::class)->findOneBy('emailCanonical', $email);
            
            if(!$user){
                $error = 'User not found!';                
                return $this->render('Neo4jUserBundle:Resetting:passwordreset.html.twig',array('error'=>$error, 'form' => $form->createView()));
            }else{
                
                /* --------__ Send Email with Token __----------- */
                
                    $password = md5(uniqid());
                    $message = \Swift_Message::newInstance()
                    ->setSubject($this->getParameter('neo4j_user.mail.subject.passwordreset'))
                    ->setFrom($this->getParameter('mailer_user'))
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'Neo4jUserBundle:Resetting:passwordresetemail.html.twig',
                            array('username' => $user->getUsername(), 'password' => $password)
                        ),
                        'text/html'
                    );
                    $this->get('mailer')->send($message); 
                    
                    $password=$this->get('security.password_encoder')->encodePassword($user, $password);
                    $user->setPassword($password);

                     // 4) save the User!
                    $em->persist($user);
                    $em->flush();
                    
                    $success = 'New password sent to your Email!';
                    return $this->render('Neo4jUserBundle:Resetting:passwordreset.html.twig',array('success'=>$success));
            }
        }
            
        return $this->render('Neo4jUserBundle:Resetting:passwordreset.html.twig',array('error'=>$error, 'form' => $form->createView()));

    }
}