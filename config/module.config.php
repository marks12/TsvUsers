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
													'route'    => '/[:controller[/:action]]',
													'constraints' => array(
															'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
															'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
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
			),

	),
);
