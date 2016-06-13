<?php

namespace JoranBeaufort\Neo4jUserBundle\Entity;

use GraphAware\Neo4j\OGM\Annotations as OGM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
    
/**
 * @OGM\Node(label="User")
 */
 
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @OGM\GraphId()
     * @var int
     */
    private $id;

    
    /**
     * @OGM\Property(type="string")
     * @var string
     */
     
    private $uid;    
    
    /**
     * @OGM\Property(type="string")
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;    
    
    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $emailCanonical;

    /**
     * @OGM\Property(type="string")
     * @var string
     * @Assert\Regex("/^[\w\d\s.,-]*$/")
     * @Assert\NotBlank()
     */
    private $username;    
    
    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $usernameCanonical;

    /**
     * @Assert\Length(min = 4, max=4096)
     */
    private $plainPassword;

    /**
     * @OGM\Property(type="string")
     * @var string
     */
     
    private $password;    
    
    
    /**
     * @OGM\Property(type="string")
     * @var string
     */
     
    private $registrationDateTime;
    
    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $profileImage;  
    
    /**
     * @Assert\Image(
     *     maxSize = "300k",
     *     minWidth = 200,
     *     maxWidth = 400,
     *     minHeight = 200,
     *     maxHeight = 400
     * )
     */
    private $profileImageFile;    
    
    
    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $profileDescription;
    
    /**
     * @OGM\Property(type="string")
     * @var string
     */
     
    private $confirmationToken;    
    
    
    /**
     * @OGM\Property(type="boolean")
     * @var boolean
     */
     
    private $isActive;    
    
    /**
     * @OGM\Property(type="boolean")
     * @var boolean
     */
     
    private $isAccountNonExpired;
    
    /**
     * @OGM\Property(type="boolean")
     * @var boolean
     */
     
    private $isAccountNonLocked;
    
    /**
     * @OGM\Property(type="boolean")
     * @var boolean
     */
     
    private $isCredentialsNonExpired;
    
    /**
     * @OGM\Property(type="boolean")
     * @var boolean
     */
     
    private $isEnabled;
    
    /**
     * @OGM\Relationship(type="HAS_ROLE", direction="OUTGOING", targetEntity="Role", collection=true, mappedBy="users")
     * @var ArrayCollection|Role[]
     */
    protected $roles;
    
    public function __construct()
    {
        $this->isActive = true;
        $this->roles = new ArrayCollection();
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

    // other properties and methods

    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;
    }

    public function getUsernameCanonical()
    {
        return $this->usernameCanonical;
    }

    public function setUsernameCanonical($usernameCanonical)
    {
        $this->usernameCanonical = $usernameCanonical;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }    
    
    public function getOldPassword()
    {
        return $this->oldPassword;
    }
    
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }    
    
    public function getNewPassword()
    {
        return $this->newPassword;
    }
    
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }    
    
    public function getRegistrationDateTime()
    {
        return $this->registrationDateTime;
    }
    
    public function setRegistrationDateTime($registrationDateTime)
    {
        $this->registrationDateTime = $registrationDateTime;
    }    
    
    public function getProfileImage()
    {
        return $this->profileImage;
    }
    
    public function setProfileImage($profileImage)
    {
        $this->profileImage = $profileImage;
    }    
    
    public function getProfileImageFile()
    {
        return $this->profileImageFile;
    }
    
    public function setProfileImageFile($profileImageFile)
    {
        $this->profileImageFile = $profileImageFile;
    }    
    
    public function getProfileDescription()
    {
        return $this->profileDescription;
    }
    
    public function setProfileDescription($profileDescription)
    {
        $this->profileDescription = $profileDescription;
    }    
    
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }   

    public function setIsAccountNonExpired($isAccountNonExpired)
    {
        $this->isAccountNonExpired = $isAccountNonExpired;
    }
    
    public function isAccountNonExpired()
    {
        return $this->isAccountNonExpired;
    }
    
    public function setIsAccountNonLocked($isAccountNonLocked)
    {
        $this->isAccountNonLocked = $isAccountNonLocked;
    }


    public function isAccountNonLocked()
    {
        return $this->isAccountNonLocked;
    }

    public function setIsCredentialsNonExpired($isCredentialsNonExpired)
    {
        $this->isCredentialsNonExpired = $isCredentialsNonExpired;
    }

    public function isCredentialsNonExpired()
    {
        return $this->isCredentialsNonExpired;
    }

    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }
    
    public function isEnabled()
    {
        return $this->isEnabled;
    }
    

    public function getSalt()
    {
        // The bcrypt algorithm doesn't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\JoranBeaufort\Neo4jUserBundle\Entity\Role[]
     */
    public function getRoles()
    {
        $roles = array();
        foreach($this->roles as $role){
            array_push($roles,$role->getRoleType());
        }
        
        return $roles;
    }
    
    
    /**
     * @param JoranBeaufort\Neo4jUserBundle\Entity\Role $role
     */
    public function addRole(Role $role)
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }
    }

    /**
     * @param JoranBeaufort\Neo4jUserBundle\Entity\Role $role
     */
    public function removeRole(Role $role)
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }
    }
    
    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    
    // other methods, including security methods like getRoles()
}