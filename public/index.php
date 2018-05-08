<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */

//only test expression to get a better understanding of the executed requests
if (PHP_SAPI != 'cli')
    //var_dump($_SERVER['REQUEST_URI']);

chdir(dirname(__DIR__));
//todo: was E_STRICT but this level doesn't show any errors in log
//make a more sound decision
//error_reporting(E_ALL);

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
    define("DIRECTORY_SEPARATOR", "\\");
else
    //define("DIRECTORY_SEPARATOR", "/");


define(
    'APPLICATION_ENV', (getenv('APPLICATION_ENV')
    ? getenv('APPLICATION_ENV')
    : 'production')
);

define('APPLICATION_ROOT', dirname(__DIR__));

// Setup autoloading
require __DIR__ . '/../vendor/autoload.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
