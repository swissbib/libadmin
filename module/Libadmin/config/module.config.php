<?php
namespace Libadmin; // Set module namespace

use Libadmin\Controller\AdminControllerFactory;
use Libadmin\Controller\ApiControllerFactory;
use Libadmin\Controller\GroupControllerFactory;
use Libadmin\Controller\HomeControllerFactory;
use Libadmin\Controller\InstitutionControllerFactory;
use Libadmin\Controller\ViewControllerFactory;
use Libadmin\Export\System\FormetaFactory;
use Libadmin\Export\System\MapPortalFactory;
use Libadmin\Export\System\VuFindFactory;
use Zend\Router\Http\Segment;
use Libadmin\Controller\InstitutionController;
use Libadmin\Controller\GroupController;
use Libadmin\Controller\ApiController;
use Libadmin\Controller\ViewController;
use Libadmin\Controller\AdminController;
use Libadmin\Controller\HomeController;





return array(
	'controllers' => array(
	    'factories' => [
            HomeController::class   => HomeControllerFactory::class,
            InstitutionController::class    => InstitutionControllerFactory::class,
            GroupController::class  =>  GroupControllerFactory::class,
            ViewController::class   =>  ViewControllerFactory::class,
            AdminController::class  =>  AdminControllerFactory::class,
            ApiController::class    =>  ApiControllerFactory::class
        ]
	),
	// The following section is new and should be added to your file
	'router' => array(
		'routes' => array(
			'institution' => array(
				'type' => Segment::class,
				'options' => array(
					'route' => '/institution[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
					),
					'defaults' => array(
						'controller' => InstitutionController::class,
						'action' => 'index',
					),
				),
			),
			'group' => array(
				'type' => Segment::class,
				'options' => array(
					'route' => '/group[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
					),
					'defaults' => array(
						'controller' => GroupController::class,
						'action' => 'index',
					),
				),
			),
			'view' => array(
				'type' => Segment::class,
				'options' => array(
					'route' => '/view[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
					),
					'defaults' => array(
						'controller' => ViewController::class,
						'action' => 'index',
					),
				),
			),
			'admin' => array(
				'type' => Segment::class,
				'options' => array(
					'route' => '/admin[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
					),
					'defaults' => array(
						'controller' => AdminController::class,
						'action' => 'index',
					),
				),
			),
			'api' => array(
				'type' => Segment::class,
				'options' => array(
					'route' => '/api/:system/:view:.:format',
					'constraints' => array(
						'system' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'view' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'format' => '(xml|json|fake|formeta)' // add more formats here
					),
					'defaults' => array(
						'controller' => ApiController::class,
						'action' => 'index'
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
			'ViewJsonStrategy','ViewFormetaStrategy'
		)
	),

	'service_manager' => array(
		'factories' => [
			'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
			'ViewFormetaStrategy' => 'Libadmin\Services\View\ViewFormetaStrategyFactory',
			'FormetaRenderer'	=>	'Libadmin\Services\View\ViewFormetaRendererFactory',
			'export_system_vufind' => VuFindFactory::class,
            'export_system_mapportal' => MapPortalFactory::class,
			'export_system_formeta' => FormetaFactory::class,
		]
	),

	/**
	 * Configure locale translator
	 * Note: Each translation file that is loaded needs to have a text_domain added,
	 *         If no text_domain is added, 'default' will be assumed.
	 *         To use translations with namespaces the respective view-helper needs to pass
	 *         the "text_domain", e.g: $this->translate('example', 'Libadmin');
	 */
	'translator' => array(
		'locale' => 'de_DE',
		'translation_file_patterns' => array(
			array(
				'type' => 'gettext',
				'base_dir' => __DIR__ . '/../language', // Directory to load gettext files from
				'pattern' => '%s.mo', // Gettext files naming pattern
				'text_domain' => 'Libadmin', // Text-domain of the translation
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
				'label' => 'navigation_institutions',
				'route' => 'institution'
			),
			'group' => array(
				'label' => 'navigation_groups',
				'route' => 'group'
			),
			'view' => array(
				'label' => 'navigation_views',
				'route' => 'view'
			),
			'admin' => array(
				'label' => 'navigation_admin',
				'route' => 'admin'
			)
		),
	),
    'libadmin' => array(

        'backlinksconfig' => 'local/config/libadmin/MapPortal.ini',
        'linkedswissbibconfig' => 'local/config/libadmin/LinkedSwissbib.ini'

    ),

);
