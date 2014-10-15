<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TsvUsers\Entity;

use BjyAuthorize\Acl\HierarchicalRoleInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * An example entity that represents a role.
 *
 * @ORM\Entity
 * @ORM\Table(name="user_role")
 */
class Role// implements HierarchicalRoleInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="roleId", length=255, unique=true)
     */
    protected $roleId;
    
    /**
     * @var int
     * @ORM\Column(type="integer", length=1)
     */
    protected $is_default;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="TsvUsers\Entity\Role")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

    protected $assigned = false;
    
    public function __construct()
    {
        $this->parent = new ArrayCollection();
    }
    
    public function assingRole()
    {
    	$this->assigned = true;
    }
    
    public function UnAssingRole()
    {
    	$this->assigned = false;
    }
    
    public function isAssignedRole()
    {
    	return $this->assigned;
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
