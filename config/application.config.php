<?php




$modules = [
    'TravelloViewHelper',
    'Zend\Serializer',
    'Zend\Log',
    'Zend\Cache',
    'Zend\Mvc\I18n',
    'Zend\Paginator',
    'Zend\Navigation',
    'Zend\Session',
    'Zend\Router',
    'Zend\Validator',

    'Zend\I18n',
    'Zend\Form',
    'Zend\InputFilter',
    'Zend\Db',
    'Zend\Filter',
    'Zend\Hydrator',
    'Zend\Mvc\Plugin\FlashMessenger',

    'Administration',
    'Application',
    'Libadmin'

];

if (PHP_SAPI == 'cli' && !defined('VUFIND_PHPUNIT_RUNNING')) {
    $modules[] = 'Zend\Mvc\Console';
}



return [
    'modules' => array_unique($modules),
    'module_listener_options' => [
        // This should be an array of paths in which modules reside.
        // If a string key is provided, the listener will consider that a module
        // namespace, the value of that key the specific path to that module's
        // Module class.
        'module_paths' => [
            './module',
            './vendor',
        ],

        // An array of paths from which to glob configuration files after
        // modules are loaded. These effectively overide configuration
        // provided by modules themselves. Paths may use GLOB_BRACE notation.


        // Whether or not to enable a configuration cache.
        // If enabled, the merged configuration will be cached and used in
        // subsequent requests.
        //'config_cache_enabled' => $booleanValue,

        // The key used to create the configuration cache file name.
        //'config_cache_key' => $stringKey,

        // Whether or not to enable a module class map cache.
        // If enabled, creates a module class map cache which will be used
        // by in future requests, to reduce the autoloading process.
        //'module_map_cache_enabled' => $booleanValue,

        // The key used to create the class map cache file name.
        //'module_map_cache_key' => $stringKey,

        // The path in which to cache merged configuration.
        //'cache_dir' => $stringPath,

        // Whether or not to enable modules dependency checking.
        // Enabled by default, prevents usage of modules that depend on other modules
        // that weren't loaded.
        // 'check_dependencies' => true,
    ]
];
