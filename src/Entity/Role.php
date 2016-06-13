<?php
namespace JoranBeaufort\Neo4jUserBundle\Entity;

// Remember to create the role nodes in the neo4j graph
// create (r:Role{roleType:'ROLE_USER'})
// Add as many roles as needed.

use Doctrine\Common\Collections\ArrayCollection;
use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * @OGM\Node(label="Role")
 */
 
class Role
{
    /**
     * @OGM\GraphId()
     * @var int
     */
    private $id;
    
    /**
     * @OGM\Relationship(type="HAS_ROLE", direction="INCOMING", targetEntity="User", collection=true)
     * @var ArrayCollection|User[]
     */
     
    protected $users;
    
    /**
     * @OGM\Property(type="string")
     * @var string
     */
     
    private $roleType;
        
    
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    
    
    public function getRoleType()
    {
        return $this->roleType;
    }

    public function setRoleType($roleType)
    {
        $this->roleType = $roleType;
    }

    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\JoranBeaufort\Neo4jUserBundle\Entity\User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param JoranBeaufort\Neo4jUserBundle\Entity\User $user
     */
    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
    }

    /**
     * @param JoranBeaufort\Neo4jUserBundle\Entity\User $user
     */
    public function removeUser(User $user)
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }
    }
}