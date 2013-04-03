<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/11/12
 * Time: 9:21 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Libadmin;

use Libadmin\Model\Institution;
use Libadmin\Model\InstitutionTable;

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
                'Libadmin\Model\InstitutionTable' =>  function($sm) {
                    $tableGateway = $sm->get('InstitutionTableGateway');
                    $table = new InstitutionTable($tableGateway);
                    return $table;
                },
                'InstitutionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Institution());
                    return new TableGateway('institution', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

}