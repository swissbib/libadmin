<?php
namespace Libadmin; // Set module namespace

use Libadmin\Controller\AdminControllerFactory;
use Libadmin\Controller\ApiControllerFactory;
use Libadmin\Controller\AdminInstitutionController;
use Libadmin\Controller\AdminInstitutionControllerFactory;
use Libadmin\Controller\GroupControllerFactory;
use Libadmin\Controller\HomeControllerFactory;
use Libadmin\Controller\InstitutionControllerFactory;
use Libadmin\Controller\ViewControllerFactory;
use Libadmin\Export\System\FormetaFactory;
use Libadmin\Export\System\GeoJsonFactory;
use Libadmin\Export\System\SearchIndexFactory;
use Libadmin\Export\System\MapPortalFactory;
use Libadmin\Export\System\VuFindFactory;
use Libadmin\Form\AdminInstitutionFieldset;
use Libadmin\Form\AdminInstitutionForm;
use Libadmin\Form\GroupForm;
use Libadmin\Form\InstitutionFieldset;
use Libadmin\Form\InstitutionForm;
use Libadmin\Form\InstitutionFormFactory;
use Libadmin\Form\ViewForm;
use Libadmin\Form\ViewFormFactory;
use Libadmin\Helper\RelationOverview;
use Libadmin\Helper\RelationOverviewFactory;
use Libadmin\Libadmin\Form\GroupFormFactory;
use Libadmin\View\Helper\FormBootstrapRow;
use Libadmin\View\Helper\FormBootstrapRowFactory;
use Libadmin\Table\AdminInstitutionTable;
use Libadmin\Table\AdminInstitutionTableFactory;
use Libadmin\Table\AdminInstitutionTableGatewayFactory;
use Libadmin\Table\AdresseTable;
use Libadmin\Table\AdresseTableFactory;
use Libadmin\Table\AdresseTableGatewayFactory;
use Libadmin\Table\GroupRelationTableFactory;
use Libadmin\Table\GroupRelationTableGatewayFactory;
use Libadmin\Table\GroupTable;
use Libadmin\Table\GroupTableFactory;
use Libadmin\Table\GroupTableGatewayFactory;
use Libadmin\Table\InstitutionAdminInstitutionRelationTable;
use Libadmin\Table\InstitutionAdminInstitutionRelationTableFactory;
use Libadmin\Table\InstitutionAdminInstitutionRelationTableGatewayFactory;
use Libadmin\Table\InstitutionRelationTableFactory;
use Libadmin\Table\InstitutionRelationTableGatewayFactory;
use Libadmin\Table\InstitutionTableFactory;
use Libadmin\Table\InstitutionTableGatewayFactory;
use Libadmin\Table\KontaktTable;
use Libadmin\Table\KontaktTableFactory;
use Libadmin\Table\KontaktTableGatewayFactory;
use Libadmin\Table\KostenbeitragTable;
use Libadmin\Table\KostenbeitragTableFactory;
use Libadmin\Table\KostenbeitragTableGatewayFactory;
use Libadmin\Table\TablePluginManager;
use Libadmin\Table\TablePluginManagerFactory;
use Libadmin\Table\ViewTable;
use Libadmin\Table\ViewTableFactory;
use Libadmin\Table\ViewTableGatewayFactory;
use Zend\Router\Http\Segment;
use Libadmin\Controller\InstitutionController;
use Libadmin\Controller\GroupController;
use Libadmin\Controller\ApiController;
use Libadmin\Controller\ViewController;
use Libadmin\Controller\AdminController;
use Libadmin\Controller\HomeController;


use Libadmin\Table\InstitutionTable;
use Libadmin\Table\InstitutionRelationTable;
use Libadmin\Table\GroupRelationTable;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            HomeController::class => HomeControllerFactory::class,
            AdminInstitutionController::class => AdminInstitutionControllerFactory::class,
            InstitutionController::class => InstitutionControllerFactory::class,
            GroupController::class => GroupControllerFactory::class,
            ViewController::class => ViewControllerFactory::class,
            AdminController::class => AdminControllerFactory::class,
            ApiController::class => ApiControllerFactory::class

        ]
    ],
    // The following section is new and should be added to your file
    'router' => [
        'routes' => [
            'institution' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/institution[/:action][/:id]',
                    'constraints' => [
                        //Ralf: Namen explizit angeben (striktes routing)
                        //(add|edit....)
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => InstitutionController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'group' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/group[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => GroupController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'view' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/view[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => ViewController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'admin' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => AdminController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'admininstitution' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admininstitution[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => AdminInstitutionController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'api' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/:system/:view:.:format',
                    'constraints' => [
                        'system' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'view' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'format' => '(xml|json|fake|formeta)' // add more formats here
                    ],
                    'defaults' => [
                        'controller' => ApiController::class,
                        'action' => 'index'
                    ]
                ]
            ]
        ],
    ],


    //in application config - mehr globale Aspekte
    'view_manager' => [
        'template_path_stack' => [
            'libadmin' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy', 'ViewFormetaStrategy'
        ]
    ],

    'service_manager' => [
        'factories' => [
            //hier Klassennmespaces
            'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'ViewFormetaStrategy' => 'Libadmin\Services\View\ViewFormetaStrategyFactory',
            'FormetaRenderer' => 'Libadmin\Services\View\ViewFormetaRendererFactory',
            'export_system_vufind' => VuFindFactory::class,
            'export_system_mapportal' => MapPortalFactory::class,
            'export_system_formeta' => FormetaFactory::class,
            'export_system_geojson' => GeoJsonFactory::class,
            'export_system_searchindex' => SearchIndexFactory::class,
            TablePluginManager::class => TablePluginManagerFactory::class

        ]
    ],


    'form_elements' => [
        'factories' => [
            InstitutionForm::class => InstitutionFormFactory::class,
            InstitutionFieldset::class => InvokableFactory::class,
            ViewForm::class => ViewFormFactory::class,
            GroupForm::class => GroupFormFactory::class,
            AdminInstitutionForm::class => InvokableFactory::class,
            AdminInstitutionFieldset::class => InvokableFactory::class
        ]
    ],

    /**
     * Configure locale translator
     * Note: Each translation file that is loaded needs to have a text_domain added,
     *         If no text_domain is added, 'default' will be assumed.
     *         To use translations with namespaces the respective view-helper needs to pass
     *         the "text_domain", e.g: $this->translate('example');
     */

    //im Application Modul abegen
    'translator' => [
        'locale' => 'de_DE',
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language', // Directory to load gettext files from
                'pattern' => '%s.mo', // Gettext files naming pattern


            ]
        ]
    ],

    //das könnte man auf Module verteilen wenn ich in einzelne Module aufgeteilt habe
    'navigation' => [
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => [
            // And finally, here is where we define our page hierarchy
            'home' => [
                'label' => 'navigation_home',
                'route' => 'home'
            ],
            'institution' => [
                'label' => 'navigation_institutions',
                'route' => 'institution'
            ],
            'group' => [
                'label' => 'navigation_groups',
                'route' => 'group'
            ],
            'view' => [
                'label' => 'navigation_views',
                'route' => 'view'
            ],
            'admininstitution' => [
                'label' => 'admininstitution',
                'route' => 'admininstitution'
            ],
            'admin' => [
                'label' => 'navigation_admin',
                'route' => 'admin'
            ],
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'formBootstrapRow'                        => FormBootstrapRow::class,
        ]
    ],
    'libadmin' => [

        'backlinksconfig' => 'local/config/libadmin/MapPortal.ini',
        'linkedswissbibconfig' => 'local/config/libadmin/LinkedSwissbib.ini',
        'exportimportconfig' => 'local/config/libadmin/ExportImport.ini',

        'tablepluginmanager' => [
            'factories' => [
                //todo strings rausnehmen!!
                InstitutionTable::class => InstitutionTableFactory::class,
                'InstitutionTableGateway' => InstitutionTableGatewayFactory::class,
                InstitutionRelationTable::class => InstitutionRelationTableFactory::class,
                'InstitutionRelationTableGateway' => InstitutionRelationTableGatewayFactory::class,
                GroupTable::class => GroupTableFactory::class,
                'GroupTableGateway' => GroupTableGatewayFactory::class,
                ViewTable::class => ViewTableFactory::class,
                'ViewTableGateway' => ViewTableGatewayFactory::class,
                GroupRelationTable::class => GroupRelationTableFactory::class,
                'GroupRelationTableGateway' => GroupRelationTableGatewayFactory::class,
                RelationOverview::class => RelationOverviewFactory::class,
                'AdminInstitutionTableGateway' => AdminInstitutionTableGatewayFactory::class,
                AdminInstitutionTable::class    => AdminInstitutionTableFactory::class,
                'AdresseTableGateway'       => AdresseTableGatewayFactory::class,
                'KontaktTableGateway'   => KontaktTableGatewayFactory::class,
                'KostenbeitragTableGateway' => KostenbeitragTableGatewayFactory::class,
                AdresseTable::class => AdresseTableFactory::class,
                KontaktTable::class => KontaktTableFactory::class,
                KostenbeitragTable::class   => KostenbeitragTableFactory::class,
                InstitutionAdminInstitutionRelationTable::class => InstitutionAdminInstitutionRelationTableFactory::class,
                'InstitutionAdminInstitutionRelationTableGateway' => InstitutionAdminInstitutionRelationTableGatewayFactory::class


            ]
        ]
    ],

];
