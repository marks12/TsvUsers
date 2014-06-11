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
use TsvUsers\Entity\Users;
use TsvUsers\Entity\Project;
use TsvUsers\Entity\Address;

class TsvUsersController extends AbstractActionController
{
// 	public function indexAction() {
// 	    $objectManager = $this
// 	        ->getServiceLocator()
// 	        ->get('Doctrine\ORM\EntityManager');
	
// 	    $users = new \TsvUsers\Entity\Users();
// 	    $users->__set('fullName','Marco Pivetta');

// 	    $objectManager->persist($users); // $user is now "managed"
// 	    $objectManager->flush();// commit changes to db
	
// 	    die(var_dump($users->__get("id"))); // yes, I'm lazy
// 	}

    public function indexAction()
    {

	    $objectManager = $this
 		        ->getServiceLocator()
   		        ->get('Doctrine\ORM\EntityManager');
    	
//     	$user1 = new Users();
//     	$user1->__set('fullName','Marco Pivetta');
//     	$objectManager->persist($user1);
    	
//     	$user2 = new Users();
//     	$user2->__set('fullName','Michaël Gallego');
//     	$objectManager->persist($user2);
    	
    	
//     	$objectManager->flush();

// 	    $user1 = $objectManager->find('TsvUsers\Entity\Users', 41);
	    
// 	    var_dump($user1->__get('fullName')); // Marco Pivetta
	    
// 	    $user2 = $objectManager
// 	    ->getRepository('TsvUsers\Entity\Users')
// 	    ->findOneBy(array('fullName' => 'Michaël Gallego'));
	    
// 	    var_dump($user2->__get('fullName')); // Michaël Gallego
	    
	    
//  	    die(var_dump($user2->__get("id"))); // yes, I'm lazy
    	
	    
// 	    $user = new Users();
// 	    $user->__set('fullName','Marco Pivetta');
// 	    $objectManager->persist($user);
	    
// 	    $address = new Address();
// 	    $address->__set('city','Frankfurt');
// 	    $address->__set('country','Germany');
// 	    $objectManager->persist($address);
	    
// 	    $project = new Project();
// 	    $project->__set('name','Doctrine ORM');
// 	    $objectManager->persist($project);
	    
// 	    $user->__set('address',$address);
// 	    $user->__get('projects')->add($project);
// 	    $objectManager->flush();
	    
	    
	    
	    
        return array();
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /tsvUsers/tsv-users/foo
        return array();
    }
}
