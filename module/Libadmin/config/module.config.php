<?php
namespace Libadmin;	// Set module namespace

return array(
    'controllers' => array(
        'invokables' => array(
            'Libadmin\Controller\Home' 			=> 'Libadmin\Controller\HomeController',
            'Libadmin\Controller\Institution'	=> 'Libadmin\Controller\InstitutionController',
            'Libadmin\Controller\Group'			=> 'Libadmin\Controller\GroupController',
            'Libadmin\Controller\View'			=> 'Libadmin\Controller\ViewController',
            'Libadmin\Controller\Admin'			=> 'Libadmin\Controller\AdminController',
            'Libadmin\Controller\Api'			=> 'Libadmin\Controller\ApiController'
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
			'api' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/api/:system/:view:.:format',
					'constraints' => array(
						'system'=> '[a-zA-Z][a-zA-Z0-9_-]*',
						'view'	=> '[a-zA-Z][a-zA-Z0-9_-]*',
						'format'=> '(xml|json|fake)' // add more formats here
					),
					'defaults' => array(
						'controller' => 'Libadmin\Controller\Api',
						'action'     => 'index'
					)
				)
			)
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'libadmin' => __DIR__ . '/../view',
        ),
		'strategies' => array(
			'ViewJsonStrategy'
		)
    ),

	'service_manager' => array(
	    'factories' => array(
	        'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
	    ),
		'invokables' => array(
			'export_system_vufind' => 'Libadmin\Export\System\Vufind'
		)
	),

	/**
	 * Configure locale translator
	 * Note: Each translation file that is loaded needs to have a text_domain added,
	 * 		 If no text_domain is added, 'default' will be assumed.
	 * 		 To use translations with namespaces the respective view-helper needs to pass
	 *		 the "text_domain", e.g: $this->translate('example', 'Libadmin');
	 */
	'translator' => array(
		'locale' => 'de_DE',
		'translation_file_patterns' => array(
			array(
				'type'			=> 'gettext',
				'base_dir'		=> __DIR__ . '/../language',	// Directory to load gettext files from
				'pattern'		=> '%s.mo',						// Gettext files naming pattern
				'text_domain'	=> 'Libadmin',					// Text-domain of the translation
			)
		)
	),

	'navigation' => array(
		// The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
		'default' => array(
			// And finally, here is where we define our page hierarchy
			'home' => array(
				'label' => 'navigation_home',
				'route' => 'home'
			),
			'institution' => array(
				'label'	=> 'navigation_institutions',
				'route'	=> 'institution'
			),
			'group' => array(
				'label'	=> 'navigation_groups',
				'route'	=> 'group'
			),
			'view' => array(
				'label'	=> 'navigation_views',
				'route'	=> 'view'
			),
			'admin' => array(
				'label'	=> 'navigation_admin',
				'route'	=> 'admin'
			)
		),
	)

);

