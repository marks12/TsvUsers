<?php 
namespace TsvUsers\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/** @ORM\Entity 
  * @ORM\Table(name="user")
  */
class User {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /** @ORM\Column(type="string", length=255) */
    protected $username;

    /** @ORM\Column(type="string", length=255) */
    protected $email;
    
    /** @ORM\Column(type="string", length=50) */
    protected $display_name;
    
    /** @ORM\Column(type="string", length=128) */
    protected $password;
    
    /** @ORM\Column(type="integer", length=5) */
    protected $state;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="TsvUsers\Entity\Role")
     * @ORM\JoinTable(name="user_role_linker",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;
        
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }
    
    /**
     * Magic getter
     * @param $property
     * @return mixed
     */
    public function __get($key)
    {
    	if(property_exists($this, $key))
    	return $this->{$key};
    	else
    	die("Requested property {$key} not exists");
    	
    }
    
    /**
     * Magic setter
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
    	if(property_exists($this, $key))
    	$this->{$key} = $value;
    	else
    	die("Requested property {$key} not exists");
    }
    
}