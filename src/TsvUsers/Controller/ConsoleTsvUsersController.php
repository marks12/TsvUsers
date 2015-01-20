<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/TsvUsers for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TsvUsers\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Element\Password;
use TsvUsers\Entity\User;
use Zend\Console\Request as ConsoleRequest;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\ColorInterface as color;
use TsvUsers\Entity\Role;
use Zend\Text\Table\Table as Table;

class ConsoleTsvUsersController extends AbstractActionController
{
	public function indexAction()
	{
		return array();
	}
   
    public function cUserlistAction()
    {
    	$console = $this->getServiceLocator()->get('console');
    	$console->write( "list of existing users \n=============\n", color::RED);
    	
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	
    	$users = $em->getRepository('TsvUsers\Entity\User')->findAll();

    	$table = new Table(array('columnWidths' => array(5,20, 40, 20, 10)));
    	$table->appendRow(array('Id','Name', 'Email', 'Roles', 'State'));

    	if(!count($users))
    	{
    		$console->write( "No users in the database\n", color::YELLOW);
    		return '';
    	}
    	
    	
    	foreach ($users as $user)
    	{
    		$roles_arr = array();
    		foreach ($user->__get('roles') as $role)
    			$roles_arr[] = $role->__get('id');
    		
    		if($user->__get('state') == '1')
    			$state = 'Active';
    		else 
    			$state = 'Disabled';
    		
    		$table->appendRow(array((string)$user->__get('user_id'),$user->__get('username'), $user->__get('email'), implode(",", $roles_arr), $state));
    		
    	}
    	 
    	$console->write( (string)$table, color::GREEN);
    	
    	return '';
    }
    
    public function cListRolesAction()
    {
    	$console = $this->getServiceLocator()->get('console');
 		$console->write( "List of existing roles \n=============\n", color::RED);
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	$roles = $em->getRepository('TsvUsers\Entity\Role')->findAll();

    	$roles_ar = array();
    	
    	if(!count($roles))
    	{
    		$console->write( "We did not find any role in DB\n"); 
    		return '';
    	}
    	else
    	{
    		$console->write( "Found (".count($roles).") ", color::GREEN);
    		foreach ($roles as $role)
    		{
    			$roles_ar[] = $role->__get('id');
    		}
    	}
    	
    	$console->write( "We have {".implode(",", $roles_ar)."} roles\n");
    		
    	return '';
    }
    
    public function getConsoleText($console, $show_title = 'Requested value', $max_length = 255, $min_length = 1)
    {
    	$console->write( "$show_title: ", color::GREEN);
    	$console->showCursor();
    	$data = $console->readLine();
    	
    	if(mb_strlen($data)>=$min_length && $data<=$max_length)
    		return $data;
    	else 
    	{   
    		$console->write( "Please insert value length > $min_length and < $max_length chars. Press CTRL+C for cancel. \n", color::YELLOW);
    		return $this->getConsoleText($console, $show_title, $max_length, $min_length);
    	}
    }
    
    public function getUserChoice($console, $show_title = 'Requested value', $values = array())
    {
    	if(!count($values))
    	{
    		$console->write( "===============\n ERROR : values array dont have any data \n===============\n", color::RED);
    		exit();
    	}
    	
    	$values_line = "one of [".implode(",", $values)."]";
    	
    	$console->write( "$show_title $values_line: ", color::GREEN);
    	$console->showCursor();
    	$data = $console->readLine();
    	
    	if(!in_array($data, $values))
        {
    		$console->write( "Please select one of values [".implode(',', $values)."]. Press CTRL+C for cancel. \n", color::YELLOW);
    		return $this->getUserChoice($console, $show_title, $values);
    	}
   		else
    		return $data;
    }
    
    public function cAddRoleAction()
    {
        $console = $this->getServiceLocator()->get('console');

        $console->write( "Add role \n=============\n", color::RED);
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

    	$role_name = $this->getConsoleText($console,"Please insert new role name");

    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	$role = new Role();
    	
    	$role->__set("id", $role_name);
    	$role->__set("roleId", $role_name);
    	
    	$is_default = $this->getUserChoice($console,"Is $role_name defult ?",array('y','n'));
    	$is_default = $is_default == 'y' ? 1 : 0;

    	$role->__set("is_default", $is_default);
    	
    	$parents = $em->getRepository('TsvUsers\Entity\Role')->findAll();
    	foreach ($parents as $par)
    		$array_parents[] = $par->__get('id');
    	
    	if(isset($array_parents))
    	{
    		$array_parents[] = '-';
    		$parent = $this->getUserChoice($console,"Please select user parent if you need or select '-' if no parents.",$array_parents); 
    		$role->__set("parent", $em->getRepository('TsvUsers\Entity\Role')->find($parent));
    	}
    	else 
    		$role->__set("parent", null);
    	
    	$em->persist($role);
    	$em->flush();
    	
    	if($is_default)
    		$default = "Default role";
    	else 
    		$default = "Role";
    	
    	$console->write("$default $role_name successfuly created\n");
    	
    	$console->write("\n");
    	return '';
    }
    
    public function cAdduserAction()
    {
    	$console = $this->getServiceLocator()->get('console');
    	$console->write( "Add user \n=============\n", color::RED);
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

    	$user = new User();
    	
		$username = $this->getConsoleText($console,"Please type user name",255,2);
    	
    	$user->__set('username', $username);
    	
    	$mail = $this->getConsoleText($console,"Please type user e-mail",255,7);
    	
    	$exists_email = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("email"=>$mail));
    	if($exists_email)
    	{
    		$console->write("User $mail already exists\n",color::RED);
    		return '';
    	}
    	
    	$password = $this->getConsoleText($console,"Please type user password",255,4);
    	
    	$user->__set('email', $mail);
    	$user->__set('display_name', $this->getConsoleText($console,"Please type user name for display",255,0));
    	$user->__set('password', $this->getServiceLocator()->get('zfcuser_user_hydrator')->getCryptoService()->create($password));
    	
    	$is_active = $this->getUserChoice($console,"Is user active ?",array('y','n'));
    	$is_active = $is_active == 'y' ? 1 : 0;
    	
    	$user->__set("state", $is_active);
    	
    	$default_roles = $em->getRepository('TsvUsers\Entity\Role')->findBy(array("is_default"=>1));
		
    	$roles_ids = array();
    	
    	if($default_roles)
    		foreach ($default_roles as $role)
    		{
    			$user->__get('roles')->add($role);
    			$roles_ids[] = $role->__get('id');
    		}
    	
    	$em->persist($user);
    	$em->flush();
    	
    	if(count($default_roles))
    		$roles_message = "Default roles [".implode(",", $roles_ids)."] was added.";
    	else 
    		$roles_message = "Default roles was not added. You can use role4user function for add role for user.";
    	
    	$console->write("User $username successfuly created. $roles_message\n");
    	
    	return '';
    }
    
    public function cRemoveUserAction()
    {
    	$console = $this->getServiceLocator()->get('console');
    	$console->write( "Remove user \n=============\n", color::RED);
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	
    	$mailOrId = $this->getConsoleText($console,"Please type user Email or user Id which you want to remove",255,1);
    	
    	if(is_numeric($mailOrId))
    		$user = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("user_id"=>$mailOrId));
    	else
    		$user = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("email"=>$mailOrId));
    	
    	$mail = $user->__get('email');
    	
    	if($user)
    	{
    		$em->remove($user);
    		$em->flush();
    		$console->write("User $mail was deleted from database\n",color::RED);
    	}
    	else 
    		$console->write("User $mail was not found in database\n",color::LIGHT_BLUE);
    	
    	return '';
    }
    
    public function cRemoveRoleAction()
    {
    	$console = $this->getServiceLocator()->get('console');
    	$console->write( "Remove role \n=============\n", color::RED);
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	
    	$role_id = $this->getConsoleText($console,"Please type role which you want to remove",255,1);
    	
   		$role = $em->getRepository('TsvUsers\Entity\Role')->findOneBy(array("id"=>$role_id));
    	
    	if($role)
    	{
    		$em->remove($role);
    		$em->flush();
    		$console->write("Role $role_id was deleted from database\n",color::RED);
    	}
    	else 
    		$console->write("Role $role_id was not found in database\n",color::LIGHT_BLUE);
    	
    	return '';
    }
    
    public function crmRFUAction()
    {
    	$console = $this->getServiceLocator()->get('console');
    	$console->write( "Remove role from user \n=============\n", color::RED);
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	
    	$mailOrId = $this->getConsoleText($console,"Please type user Email or user Id which you want to remove",255,1);
    	
    	if(is_numeric($mailOrId))
    		$user = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("user_id"=>$mailOrId));
    	else
    		$user = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("email"=>$mailOrId));
    	
    	$mail = $user->__get('email');
    	
    	if($user)
    	{
    		if(!count($user->__get('roles')))
    		{
    			$console->write("User $mail have not any roles\n",color::RED);
    			return '';
    		}
    		
    		foreach ($user->__get('roles') as $role)
    			$user_roles[] = $role->__get('id');
    		
    		$role4delete = $this->getUserChoice($console,"Plese select role for delete from this user",$user_roles);
    		
    		foreach ($user->__get('roles') as $role)
	   			if($role->__get('id') == $role4delete)
	   			{
	   				$user->__get('roles')->removeElement($role);
	   				$removed = $role->__get('id');
	   			}
    		
    		$em->persist($user);
    		$em->flush();
    		
    		$console->write("Role $removed remove from user $mail\n",color::RED);
    		
       	}
    	else
    		$console->write("User $mail not found in database\n",color::LIGHT_BLUE);
    	
    	return '';
    }
    
    public function cRole4UserAction()
    {
    	$console = $this->getServiceLocator()->get('console');
    	$console->write( "Add role for user \n=============\n", color::RED);
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	
    	$mailOrId = $this->getConsoleText($console,"Please type user Email or Id which you want to remove some roles",255,1);
    	
    	if(is_numeric($mailOrId))
    		$user = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("user_id"=>$mailOrId));
    	else
    		$user = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("email"=>$mailOrId));
    	
    	$mail = $user->__get('email');
    	
    	$user_roles = array();
    	foreach ($user->__get('roles') as $role)
    		$user_roles[] = $role->__get('id');
    	
    	if(!count($user_roles))
    		$exists_roles_mess = "User have not roles yet.";
    	else 
    		$exists_roles_mess = "User allready have roles [".implode(",", $user_roles)."].";

    	$console->write("You select user {$user->__get('username')}. $exists_roles_mess\n",color::YELLOW);
    	
    	$all_roles = $em->getRepository('TsvUsers\Entity\Role')->findAll();
    	
    	$not_exists_roles = array();

    	foreach ($all_roles as $role)
    		if(!in_array($role->__get('id'),$user_roles))
    			$not_exists_roles[] = $role->__get('id');
    	
    	if(!count($not_exists_roles))
    	{
    		$console->write("User $mail have all existing roles. Nothing to add.\n",color::GREEN);
    		return '';
    	}
    		
    	$new_role = $this->getUserChoice($console,"Plese select role adding for user '{$user->__get('username')}'",$not_exists_roles);

    	$role = $em->getRepository('TsvUsers\Entity\Role')->findOneBy(array("id"=>$new_role));
    	
    	$user->__get('roles')->add($role);
    	
    	$em->persist($user);
    	$em->flush();
    	
    	$console->write("Role $new_role was successfuly added for user {$user->__get('username')}\n",color::GREEN);
    	
    	return '';
    }
    
    public function cResetPassAction()
    {
    	$console = $this->getServiceLocator()->get('console');
    	$console->write( "Change user password \n=============\n", color::RED);
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	 
    	$mailOrId = $this->getConsoleText($console,"Please type user Email or Id which you want to reset password",255,1);
    	 
    	if(is_numeric($mailOrId))
    		$user = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("user_id"=>$mailOrId));
    	else
    		$user = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("email"=>$mailOrId));
    	 
    	$mail = $user->__get('email');
    	
    	$password = $this->getConsoleText($console,"Please type user password",255,4);
    	 
    	$user->__set('password', $this->getServiceLocator()->get('zfcuser_user_hydrator')->getCryptoService()->create($password));
    	
    	$console->write("Password was successfuly changed for user $mail\n",color::GREEN);
    	
    	return '';
    }
    
    public function cdrauAction()
    {
    	$console = $this->getServiceLocator()->get('console');
    	$console->write( "Adding default roles [user,admin] and default administrator\n=============\n", color::RED);
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	$roles = $em->getRepository('TsvUsers\Entity\Role')->findBy(array("id"=>array("admin","user")));
    	
    	if(count($roles))
    	{
    		$console->write("We found standart roles in database. We cant add default roles when it existing.\n",color::RED);
    		$this->cListRolesAction();
    		return '';
    	}
    	 
    	$role1 = new Role();
    	$role1->__set("id", 'user');
    	$role1->__set("roleId", 'user');
    	$role1->__set("is_default", 1);
    	$role1->__set("parent", null);
    	$em->persist($role1);
    	$em->flush();
    	
    	$role2 = new Role();
    	$role2->__set("id", 'admin');
    	$role2->__set("roleId", 'admin');
    	$role2->__set("is_default", 0);
    	$role2->__set("parent", $role1);
    	$em->persist($role2);
    	$em->flush();
    	
    	$user = new User();
    	 
    	$username = 'Admin';
    	$user->__set('username', $username);
    	 
    	$mail = $this->getConsoleText($console,"Please type user e-mail",255,7);
    	$exists_email = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("email"=>$mail));
    	if($exists_email)
    	{
    		$console->write("User $mail already exists\n",color::RED);
    		return '';
    	}
    	 
    	$password = $this->getConsoleText($console,"Please type user password",255,4);
    	 
    	$user->__set('email', $mail);
    	$user->__set('display_name', 'Administrator');
    	$user->__set('password', $this->getServiceLocator()->get('zfcuser_user_hydrator')->getCryptoService()->create($password));
    	$user->__set("state", 1);
    	 
    	$default_roles = $em->getRepository('TsvUsers\Entity\Role')->findBy(array("id"=>array('user','admin')));
    	
    	$roles_ids = array();

    	if($default_roles)
    	foreach ($default_roles as $role)
    	{
    		$user->__get('roles')->add($role);
    		$roles_ids[] = $role->__get('id');
    	}
    	 
    	$em->persist($user);
    	$em->flush();
    	  	
    	$this->cListRolesAction();
    	$this->cUserlistAction();   	
    	
    	return '';
    }
}
