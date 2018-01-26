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

		$app = $e->getApplication();
		$em  = $app->getEventManager()->getSharedManager();
		$sm  = $app->getServiceManager();

		$em->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function($e) use ($sm) {

			$routeParams = $e->getRouteMatch()->getParams();
			if (array_key_exists('format',$routeParams) && strcmp($routeParams['format'],'formeta') == 0 ) {
				$strategy = $sm->get('ViewFormetaStrategy');
				$view     = $sm->get('ViewManager')->getView();
				$strategy->attach($view->getEventManager());
			}

			$t = "";

		});


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
		return [];
	}
}
