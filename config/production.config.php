<?php

return [
    'modules' => [
    ],

    'module_listener_options' => [
        'config_glob_paths' => [
            APPLICATION_ROOT
            . '/config/autoload/{,*.}{global,production,local}.php',
        ],
    ],
];