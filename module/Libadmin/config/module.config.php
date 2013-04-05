<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Libadmin\Controller\Home' 			=> 'Libadmin\Controller\HomeController',
            'Libadmin\Controller\Institution'	=> 'Libadmin\Controller\InstitutionController',
            'Libadmin\Controller\Group'			=> 'Libadmin\Controller\GroupController',
            'Libadmin\Controller\View'			=> 'Libadmin\Controller\ViewController',
            'Libadmin\Controller\Admin'			=> 'Libadmin\Controller\AdminController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'institution' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/institution[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Libadmin\Controller\Institution',
                        'action'     => 'index',
                    ),
                ),
            ),
			'group' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/group[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Libadmin\Controller\Group',
						'action'     => 'index',
					),
				),
			),
			'view' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/view[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Libadmin\Controller\View',
						'action'     => 'index',
					),
				),
			),
			'admin' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/admin[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Libadmin\Controller\Admin',
						'action'     => 'index',
					),
				),
			),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'libadmin' => __DIR__ . '/../view',
        ),
    ),

	'service_manager' => array(
	    'factories' => array(
	        'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
	    )
	),

	'navigation' => array(
		// The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
		'default' => array(
			// And finally, here is where we define our page hierarchy
			'home' => array(
				'label' => 'Home',
				'route' => 'home'
			),
			'institution' => array(
				'label'	=> 'Institutions',
				'route'	=> 'institution'
			),
			'group' => array(
				'label'	=> 'Groups',
				'route'	=> 'group'
			),
			'view' => array(
				'label'	=> 'Views',
				'route'	=> 'view'
			),
			'admin' => array(
				'label'	=> 'Admin',
				'route'	=> 'admin'
			)
		),
	)
);

