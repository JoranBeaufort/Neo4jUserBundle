<?php
namespace JoranBeaufort\Neo4jUserBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use JoranBeaufort\Neo4jUserBundle\Manager\GraphManager;
use JoranBeaufort\Neo4jUserBundle\Entity\User;

class UserProvider implements UserProviderInterface
{
    protected $graphManager;

    public function __construct(GraphManager $graphManager)
    {
        $this->graphManager = $graphManager;
    }
    
    public function loadUserByUsername($login)
    {
        $em = $this->graphManager->getClient();
        // make a call to your webservice here
        $user = $em->getRepository(User::class)->findOneBy('usernameCanonical', $login);
        if(!$user){
            $user=$em->getRepository(User::class)->findOneBy('emailCanonical', $login);
        }
        
        if($user){
            if($user->isEnabled()===true){
                return $user;
            }else{
                throw new DisabledException(sprintf('Bitte bestätigen Sie ihre Emailadresse.', $user->getUsername()));
            }
        }else{   
            throw new UsernameNotFoundException(sprintf('Benutzername/Email "%s" wurde nicht gefunden.', $login));
        }
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Neo4jUserBundle\Entity\User';
    }
}