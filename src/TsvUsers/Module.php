<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/TsvUsers for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TsvUsers;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module implements AutoloaderProviderInterface, ConsoleUsageProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    
    public function getServiceConfig()
    {
    	return array(
    			'invokables' => array(
//     					'TsvDirectory\ContentProvider' => 'TsvDirectory\ContentProvider',
    					'ZfcUser\Form\Login'                => 'TsvUsers\Form\Login',
    			),
    			'factories' => array(
//     					'zfcuser_module_options'                        => 'ZfcUser\Factory\ModuleOptionsFactory',

    			),
//     			'aliases' => array(
//     					'zfcuser_register_form_hydrator' => 'zfcuser_user_hydrator'
//     			),
    	);
    }
    
    public function getConsoleUsage(Console $console){
    	return array(
    			// Describe available commands
    			'userlist'		=> 'List exists users',
    			'adduser'		=> 'Add new user',
    			'removeuser'	=> 'Remove user from database',
    			'listroles'		=> 'List exists roles',
    			'addrole'		=> 'Add new role to database',
    			'removerole'	=> 'Remove role from database',
    			'role4user'		=> 'Set role for users',
    			'rmRFU'			=> 'Remove role from user',
    			'resetpass'		=> 'Reset user password',
    			'drau'			=> 'Adding default roles [user,admin] and default user Admin with your password',
    
    			// Describe expected parameters
    			array( 'Use this commands for manage users and roles in TsvUsers module' ),
    	);
    }
}
