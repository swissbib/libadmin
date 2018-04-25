<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */

//only test expression to get a better understanding of the executed requests
if (PHP_SAPI != 'cli')
    var_dump($_SERVER['REQUEST_URI']);

chdir(dirname(__DIR__));
//todo: was E_STRICT but this level doesn't show any errors in log
//make a more sound decision
error_reporting(E_ERROR);

define(
    'APPLICATION_ENV', (getenv('APPLICATION_ENV')
    ? getenv('APPLICATION_ENV')
    : 'production')
);


// Setup autoloading
require __DIR__ . '/../vendor/autoload.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
