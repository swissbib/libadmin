<?php

return [
    'modules' => [
        'Zf2Whoops',
        'ZendDeveloperTools',
        'SanSessionToolbar',
    ],

    'module_listener_options' => [
        'config_glob_paths' => [
            APPLICATION_ROOT
            . '/config/autoload/{,*.}{global,development,local}.php',
        ],

        'config_cache_enabled'     => false,
        'module_map_cache_enabled' => false,
    ],
];