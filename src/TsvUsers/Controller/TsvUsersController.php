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
use Zend\View\Model\ViewModel;
use Zend\Form\Element\Password;
use TsvUsers\Entity\User;
use Zend\Console\Request as ConsoleRequest;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

class TsvUsersController extends AbstractActionController
{
    public function indexAction()
    {
    	$vm = new ViewModel();
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	$users = $em->getRepository('TsvUsers\Entity\User')->findAll();
    	$vm->setVariable("users", $users);
        return $vm;
    }

    public function editAction()
    {
    	$vm = new ViewModel();
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	$user = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("user_id"=>$this->getEvent()->getRouteMatch()->getParam('id')));
    	$roles = $em->getRepository('TsvUsers\Entity\Role')->findAll();
    	if(!$user)
    		return $this->redirect()->toRoute('zfcadmin/tsv-users/default');
    	$request = $this->getRequest();
    	
//     	$this->backwardCompatibility = false;
//     	$this->cost = 14;
//     	var_dump($user->__get('password'));
//     	var_dump($this->getServiceLocator()->get('zfcuser_user_hydrator'));
//     	var_dump($this->getServiceLocator()->get('zfcuser_user_hydrator')->getCryptoService()->create('virq3t4'));
//     	var_dump($this->->getCryptoService()->create($data['newCredential']));
//     	$this->serviceManager->get('zfcuser_user_hydrator')
//     	$this->formHydrator = $this->serviceManager->get('zfcuser_user_hydrator');
//     	exit();

    	if($request->isPost())
    	{
    		$user->__set('username',$request->getPost()->username);
    		$user->__set('display_name',$request->getPost()->display_name);
    		$user->__set('email',$request->getPost()->email);
    		
    		if(trim($request->getPost()->password))
    			$user->__set('password',$this->getServiceLocator()->get('zfcuser_user_hydrator')->getCryptoService()->create($request->getPost()->password));
    		
    		$em->persist($user);
    		$em->flush();

    		/**
    		 * roles
    		 */
    		foreach ($user->__get('roles') as $role)
    			$user->__get('roles')->removeElement($role);
    		
    		$roles = $em->getRepository('TsvUsers\Entity\Role')->findBy(array("roleId"=>$request->getPost()->roles));

    		foreach ($roles as $role)
    		{
    			$user->__get('roles')->add($role);
    		}
    		
    		$em->persist($user);
    		$em->flush();
    		
    		return $this->redirect()->toRoute('zfcadmin/tsv-users/default');
    	}

    	foreach ($roles as $role)
    	{
   			foreach ($user->__get('roles') as $user_role)
   				if($user_role->__get('roleId') == $role->__get('roleId'))
   					$role->assingRole();
    	}
    	
    	$vm->setVariable("user", $user);
    	$vm->setVariable("roles", $roles);
    	
        return $vm;
    }

    public function deleteAction()
    {
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		$user = $em->getRepository('TsvUsers\Entity\User')->findOneBy(array("user_id"=>$this->getEvent()->getRouteMatch()->getParam('id')));
		
		$em->remove($user);
		$em->flush();
		
		return $this->redirect()->toRoute('zfcadmin/tsv-users/default');
    }
    public function addAction()
    {    	
    	$vm = new ViewModel();
    	$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	$roles = $em->getRepository('TsvUsers\Entity\Role')->findAll();
    	
    	$vm->setVariable("roles",$roles);
    	
    	$request = $this->getRequest();
    	
    	if($request->isPost())
    	{
    		$user = new User();
    		
    		$user->__set('username',$request->getPost()->username);
    		$user->__set('display_name',$request->getPost()->display_name);
    		$user->__set('email',$request->getPost()->email);
    		$user->__set('state',1);
    		
   			$user->__set('password',$this->getServiceLocator()->get('zfcuser_user_hydrator')->getCryptoService()->create($request->getPost()->password));

    		$em->persist($user);
    		$em->flush();
    	
    		$roles = $em->getRepository('TsvUsers\Entity\Role')->findBy(array("roleId"=>$request->getPost()->roles));
    	
    		foreach ($roles as $role)
    		{
    			$user->__get('roles')->add($role);
    		}
    	
    		$em->persist($user);
    		$em->flush();
    	
    		return $this->redirect()->toRoute('zfcadmin/tsv-users/default');
    	}
    	
    	return $vm;
// 		return $this->redirect()->toRoute('zfcadmin/tsv-users/default');
    }

}
