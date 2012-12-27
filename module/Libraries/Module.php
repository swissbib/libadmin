<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/11/12
 * Time: 9:21 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Libraries;

use Libraries\Model\Library;
use Libraries\Model\LibraryTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;


class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Libraries\Model\LibrariesTable' =>  function($sm) {
                    $tableGateway = $sm->get('LibrariesTableGateway');
                    $table = new LibraryTable($tableGateway);
                    return $table;
                },
                'LibrariesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Library());
                    return new TableGateway('library', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

}