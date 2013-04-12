<?php
namespace Libadmin\Export\System;

use Zend\View\Model\JsonModel;
use Zend\Http\Response as HttpResponse;

/**
 * [Description]
 *
 */
class System {

	/** @var String */
	protected $view;

	/** @var String */
	protected $format;

	/** @var Array */
	protected $options;

	/** @var HttpResponse */
	protected $response;



	/**
	 * Set view
	 *
	 * @param	String		$view
	 */
	public function setView($view) {
		$this->view	= $view;
	}



	/**
	 * Get view
	 *
	 * @return	String
	 */
	public function getView() {
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