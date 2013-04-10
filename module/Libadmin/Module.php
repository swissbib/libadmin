<?php
namespace Libadmin;

use Libadmin\Model\Institution;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\InstitutionForm;
use Libadmin\Model\Group;
use Libadmin\Table\GroupTable;
use Libadmin\Form\GroupForm;
use Libadmin\Model\View;
use Libadmin\Table\ViewTable;
use Libadmin\Form\ViewForm;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;



class Module {

	/**
	 * @param	MvcEvent	$e
	 */
	public function onBootStrap(MvcEvent $e)
	{
		$translator	= $e->getApplication()->getServiceManager()->get('translator');
		$translator->setLocale('de_DE')/*->setFallbackLocale('en_US')*/;
	}



	public function getAutoloaderConfig() {
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



	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}



	public function getServiceConfig() {
		return array(
			'factories' => array(
				'Libadmin\Table\InstitutionTable' => function ($sm) {
					$tableGateway = $sm->get('InstitutionTableGateway');
					return new InstitutionTable($tableGateway);
				},
				'InstitutionTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Institution());
					return new TableGateway('institution', $dbAdapter, null, $resultSetPrototype);
				},
				'Libadmin\Table\GroupTable' => function ($sm) {
					$tableGateway = $sm->get('GroupTableGateway');
					return new GroupTable($tableGateway);
				},
				'GroupTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Group());
					return new TableGateway('group', $dbAdapter, null, $resultSetPrototype);
				},
				'Libadmin\Table\ViewTable' => function ($sm) {
					$tableGateway = $sm->get('ViewTableGateway');
					return new ViewTable($tableGateway);
				},
				'ViewTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new View());
					return new TableGateway('view', $dbAdapter, null, $resultSetPrototype);
				},
				'GroupForm' => function($sm) {
					$viewTable = $sm->get('Libadmin\Table\ViewTable');

					return new GroupForm($viewTable);
				},
				'ViewForm' => function($sm) {
					$groupTable = $sm->get('Libadmin\Table\GroupTable');

					return new ViewForm($groupTable);
				}
			)
		);
	}

}