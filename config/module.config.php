<?php

return array(
	'doctrine' => array(
		'driver' => array(
			'application_entities' => array(
				'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/TsvUsers/Entity'),
			),
			'orm_default' => array(
				'drivers' => array(
					'TsvUsers\Entity' => 'application_entities',
				),
			),
		),
	),
    'controllers' => array(
        'invokables' => array(
            'TsvUsers\Controller\TsvUsers' => 'TsvUsers\Controller\TsvUsersController',
            'TsvUsers\Controller\ConsoleTsvUsers' => 'TsvUsers\Controller\ConsoleTsvUsersController',
        ),
    ),
    'router' => array(
        'routes' => array(
    		'zfcadmin' => array(
				'child_routes' => array(						
					'tsv-users' => array(
							'type'    => 'Literal',
							'options' => array(
									// Change this to something specific to your module
									'route'    => '/tsvUsers',
									'defaults' => array(
											// Change this value to reflect the namespace in which
											// the controllers for your module are found
											'__NAMESPACE__' => 'TsvUsers\Controller',
											'controller'    => 'TsvUsers',
											'action'        => 'index',
									),
							),
							'may_terminate' => true,
							'child_routes' => array(
									// This route is a sane default when developing a module;
									// as you solidify the routes for your module, however,
									// you may want to remove it and replace it with more
									// specific routes.
									'default' => array(
											'type'    => 'Segment',
											'options' => array(
													'route'    => '/[:action][/:id]',
													'constraints' => array(
															'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
															'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
															'id'     => '[0-9]*',
													),
													'defaults' => array(
													),
											),
									),
							),
					),
        		),
    		),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'TsvUsers' => __DIR__ . '/../view',
        ),
    ),
	'navigation' => array(
			'admin' => array(
					'users' => array(
							'label' => 'Пользователи',
							'route' => 'zfcadmin/tsv-users',
					),
					'logout' => array(
							'label' => 'Выход',
							'route' => 'zfcuser/logout',
							'order' => 100,
					),
			),

	),
	'service_manager' => array(
		'invokables' => array(
			'ZfcUser\Form\Login'	=>	'TsvUsers\Form\Login',
		),
	),
	'console' => array(
			'router' => array(
					'routes' => array(
							'userlist' => array(
									'options' => array(
											'route'    => 'userlist',
											'defaults' => array(
													'controller' => 'TsvUsers\Controller\ConsoleTsvUsers',
													'action'     => 'cUserlist'
											),
									),
							),
							'adduser' => array(
									'options' => array(
											'route'    => 'adduser',
											'defaults' => array(
													'controller' => 'TsvUsers\Controller\ConsoleTsvUsers',
													'action'     => 'cAdduser'
											),
									),
							),
							'removeuser' => array(
									'options' => array(
											'route'    => 'removeuser',
											'defaults' => array(
													'controller' => 'TsvUsers\Controller\ConsoleTsvUsers',
													'action'     => 'cRemoveUser'
											),
									),
							),
							'listroles' => array(
									'options' => array(
											'route'    => 'listroles',
											'defaults' => array(
													'controller' => 'TsvUsers\Controller\ConsoleTsvUsers',
													'action'     => 'cListRoles'
											),
									),
							),
							'addrole' => array(
									'options' => array(
											'route'    => 'addrole',
											'defaults' => array(
													'controller' => 'TsvUsers\Controller\ConsoleTsvUsers',
													'action'     => 'cAddRole'
											),
									),
							),
							'role4user' => array(
									'options' => array(
											'route'    => 'role4user',
											'defaults' => array(
													'controller' => 'TsvUsers\Controller\ConsoleTsvUsers',
													'action'     => 'cRole4User'
											),
									),
							),
							'rmRFU' => array(
									'options' => array(
											'route'    => 'rmRFU',
											'defaults' => array(
													'controller' => 'TsvUsers\Controller\ConsoleTsvUsers',
													'action'     => 'crmRFU'
											),
									),
							),
							'resetpass' => array(
									'options' => array(
											'route'    => 'resetpass',
											'defaults' => array(
													'controller' => 'TsvUsers\Controller\ConsoleTsvUsers',
													'action'     => 'cResetPass'
											),
									),
							),
							'drau' => array(
									'options' => array(
											'route'    => 'drau',
											'defaults' => array(
													'controller' => 'TsvUsers\Controller\ConsoleTsvUsers',
													'action'     => 'cdrau'
											),
									),
							),
							'removerole' => array(
									'options' => array(
											'route'    => 'removerole',
											'defaults' => array(
													'controller' => 'TsvUsers\Controller\ConsoleTsvUsers',
													'action'     => 'cRemoveRole'
											),
									),
							),
					),
			),
	),
);
