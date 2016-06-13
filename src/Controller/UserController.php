<?php
// src/Acme/HelloBundle/Controller/HelloController.php
namespace JoranBeaufort\Neo4jUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Form\FormError;

use JoranBeaufort\Neo4jUserBundle\Entity\User;
use JoranBeaufort\Neo4jUserBundle\Form\UserEditType;
use JoranBeaufort\Neo4jUserBundle\Security\User\UserProvider;



class UserController extends Controller
{
    public function profileAction($slug)
    {
        
        $em = $this->get('neo4j.graph_manager')->getClient();
        $user = $em->getRepository(User::class)->findOneBy('usernameCanonical', $slug);
        
        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $slug));
        }

        return $this->render('Neo4jUserBundle:Profile:show.html.twig',array('user' => $user));
    }
    
    public function profileEditAction($slug, Request $request)
    {
        $error=null;
        
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }      
        if($slug===$this->getUser()->getUsername())
        {
            $em = $this->get('neo4j.graph_manager')->getClient();
            $user = $em->getRepository(User::class)->findOneBy('usernameCanonical', $this->getUser()->getUsername());
        
            $form = $this->createForm(UserEditType::class, $user);
            $form->handleRequest($request);
         
            if ($form->isSubmitted() && $form->isValid()) {
            $profileImage = $user->getProfileImageFile();  
                if($profileImage){
                    $profileImageName = md5(uniqid()).'.'.$profileImage->guessExtension();
                    $profileImage->move(
                        $this->getParameter('neo4j_user.directory').'/'.$user->getUid(),
                        $profileImageName
                    );
                    
                    $user->setProfileImage($profileImageName);
                }
                
                if($user->getPlainPassword() !== null){
                    $password=$this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($password);
                }                

                $uniqueEmail = $em->getRepository(User::class)->findOneBy('emailCanonical', mb_convert_case($user->getEmail(), MB_CASE_LOWER, "UTF-8"));
                
                if($this->getUser()->getEmail() !== $user->getEmail() && !$uniqueEmail){
                    
                    /* --------__ Send Email with Token __----------- */
                    $tokenGenerator=$this->get('neo4j.token_generator');
                    $confirmationToken=$tokenGenerator->generateConfirmationToken(24);
                    $message = \Swift_Message::newInstance()
                    ->setSubject($this->getParameter('neo4j_user.mail.subject.emailedit'))
                    ->setFrom($this->getParameter('mailer_user'))
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'Neo4jUserBundle:Profile:email.html.twig',
                            array('username' => $user->getUsername(), 'confirmationToken' => $confirmationToken)
                        ),
                        'text/html'
                    );
                    $this->get('mailer')->send($message); 
                    $user->setConfirmationToken($confirmationToken);
                    $user->setIsEnabled(false);
                    $user->setEmailCanonical(mb_convert_case($user->getEmail(), MB_CASE_LOWER, "UTF-8"));
                     // 4) save the User!
                    $em->persist($user);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('neo4j_register_check_email'));
                }
                
                // 4) save the User!
                $em->persist($user);
                $em->flush();
    
                return new RedirectResponse($this->generateUrl('neo4j_profile', array('slug' => $user->getUsername())));
            }
            
            return $this->render('Neo4jUserBundle:Profile:edit.html.twig',array('error'=>$error, 'form' => $form->createView(),'slug'=>$slug, 'user'=>$user));
        }else{
            $error='You do not have permission to edit this profile!';
            return $this->render('Neo4jUserBundle:Profile:edit.html.twig',array('error'=>$error));
        }
    }
    
    public function confirmUpdatedEmailAction(Request $request, $token)
    {
        $error = false;
        $em = $this->get('neo4j.graph_manager')->getClient();
        $user=$em->getRepository(User::class)->findOneBy('confirmationToken', $token);
        
        if (!$user) {
            $error = true;
            $message="The user with confirmation token ".$token." does not exist";
            return $this->render('Neo4jUserBundle:Registration:confirmed.html.twig', array('error' => $error, 'message' => $message));
        }else{
            
            $user->setConfirmationToken(null);
            $user->setIsEnabled(true);
            
            // 4) save the User!
            $em->persist($user);
            $em->flush();

            $message="Your account has been enabled. Thank you!";
            return $this->render('Neo4jUserBundle:Registration:confirmed.html.twig', array('error' => $error, 'message' => $message));
        }
    }

}