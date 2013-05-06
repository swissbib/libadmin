<?php
namespace Libadmin;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;

use Libadmin\Model\Institution;
use Libadmin\Model\InstitutionRelation;
use Libadmin\Table\InstitutionRelationTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Model\Group;
use Libadmin\Table\GroupTable;
use Libadmin\Form\GroupForm;
use Libadmin\Model\View;
use Libadmin\Table\ViewTable;
use Libadmin\Form\ViewForm;
use Libadmin\Helper\RelationOverview;
use Libadmin\Table\GroupRelationTable;

class Module
{

	/**
	 * @param    MvcEvent    $e
	 */
	public function onBootStrap(MvcEvent $e)
	{
		$translator = $e->getApplication()->getServiceManager()->get('translator');
		$translator->setLocale('de_DE'); /*->setFallbackLocale('en_US')*/
	}



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
				'Libadmin\Table\InstitutionTable' => function ($sm) {
					$institutionTableGateway = $sm->get('InstitutionTableGateway');
					$institutionRelationTable = $sm->get('Libadmin\Table\InstitutionRelationTable');
					return new InstitutionTable($institutionTableGateway, $institutionRelationTable);
				},
				'InstitutionTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Institution());
					return new TableGateway('institution', $dbAdapter, null, $resultSetPrototype);
				},
				'Libadmin\Table\GroupTable' => function ($sm) {
					$tableGateway = $sm->get('GroupTableGateway');
					$institutionRelationTable = $sm->get('Libadmin\Table\InstitutionRelationTable');
					return new GroupTable($tableGateway, $institutionRelationTable);
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
				'Libadmin\Table\GroupRelationTable' => function ($sm) {
					$tableGateway = $sm->get('GroupRelationTableGateway');
					return new GroupRelationTable($tableGateway);
				},
				'GroupRelationTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					return new TableGateway('mm_group_view', $dbAdapter);
				},
				'Libadmin\Table\InstitutionRelationTable' => function ($sm) {
					$tableGateway = $sm->get('InstitutionRelationTableGateway');
					return new InstitutionRelationTable($tableGateway);
				},
				'InstitutionRelationTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new InstitutionRelation());
					return new TableGateway('mm_institution_group_view', $dbAdapter, null, $resultSetPrototype);
				},
//				'GroupForm' => function ($sm) {
//					$viewTable = $sm->get('Libadmin\Table\ViewTable');
//
//					return new GroupForm($viewTable);
//				},
				'ViewForm' => function ($sm) {
					$groupTable = $sm->get('Libadmin\Table\GroupTable');

					return new ViewForm($groupTable);
				},
				'RelationOverviewHelper' => function ($sm) {
					$groupTable = $sm->get('Libadmin\Table\GroupTable');
					$institutionTable = $sm->get('Libadmin\Table\InstitutionTable');

					return new RelationOverview($groupTable, $institutionTable);
				}
			)
		);
	}
}
