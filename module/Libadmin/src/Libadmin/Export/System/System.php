<?php
namespace Libadmin\Export\System;

use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\JsonModel;
use Zend\Http\Response as HttpResponse;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Libadmin\Model\View;
use Libadmin\Table\GroupTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\ViewTable;

/**
 * [Description]
 *
 */
class System implements ServiceLocatorAwareInterface {

	/** @var String */
	protected $viewCode;

	/** @var View */
	protected $view;

	/** @var String */
	protected $format;

	/** @var Array */
	protected $options;

	/** @var HttpResponse */
	protected $response;

	/** @var  ServiceManager */
	protected $serviceLocator;

	/** @var InstitutionTable */
	protected $institutionTable;

	/** @var GroupTable */
	protected $groupTable;

	/** @var ViewTable */
	protected $viewTable;



	/**
	 * Set service locator
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}



	/**
	 * Get service locator
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}



	public function init() {
		$this->institutionTable	= $this->getServiceLocator()->get('Libadmin\Table\InstitutionTable');
		$this->groupTable		= $this->getServiceLocator()->get('Libadmin\Table\GroupTable');
		$this->viewTable		= $this->getServiceLocator()->get('Libadmin\Table\ViewTable');
	}




	/**
	 * Set view
	 *
	 * @param	String		$view
	 */
	public function setViewCode($view) {
		$this->viewCode	= $view;
	}



	/**
	 * Get view
	 *
	 * @return String
	 */
	public function getViewCode() {
		return $this->viewCode;
	}



	/**
	 * Get view object
	 *
	 * @throws	\Exception
	 * @return	View|null
	 */
	protected function getView() {
		if( $this->view === null && !empty($this->viewCode) && $this->viewTable !== null ) {
			$view	= $this->viewTable->getViewByCode($this->viewCode);

			if( !$view ) {
				throw new \Exception('Unknown view "' . $this->viewCode . '"');
			}

			$this->view = $view;
		}

		return $this->view;
	}



	/**
	 * Set options
	 *
	 * @param	Array	$options
	 */
	public function setOptions(array $options) {
		$this->options = $options;
	}



	/**
	 * Get options
	 *
	 * @return	Array
	 */
	public function getOptions() {
		return $this->options;
	}



	/**
	 * Get option
	 *
	 * @param	String		$name
	 * @param	Mixed		$default
	 * @return	Mixed
	 */
	public function getOption($name, $default = null) {
		return isset($this->options[$name]) ? $this->options[$name] : $default;
	}



	/**
	 * Set response
	 * Current response object to set custom headers
	 *
	 * @param	HttpResponse	$response
	 */
	public function setResponse($response) {
		$this->response = $response;
	}



	/**
	 * Get data for response
	 *
	 * @param	String		$format
	 * @return	Mixed
	 * @throws	\Exception
	 */
	public function getData($format) {
		$method	= 'get' . ucfirst($format) . 'Data';

		if( !method_exists($this, $method) ) {
			throw new \Exception('Unknown export format "' . $format . '"');
		}

		return $this->$method();
	}



	/**
	 * Get data as json (model)
	 *
	 * @throws \Exception
	 * @return	JsonModel
	 */
	public function getJsonData() {
		throw new \Exception('Json export is not supported for system "' . get_class($this) . '"');
	}



	/**
	 * Get data as xml string
	 *
	 * @throws \Exception
	 * @return	String
	 */
	public function getXmlData() {
		throw new \Exception('Xml export is not supported for system "' . get_class($this) . '"');
	}

}