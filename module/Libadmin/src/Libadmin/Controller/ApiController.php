<?php
namespace Libadmin\Controller;

use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Controller\AbstractActionController;

use Libadmin\Export\System\System;

/**
 * Export api controller
 *
 */
class ApiController extends AbstractActionController
{

	/**
	 * Handle export request
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function indexAction()
	{
		$serviceName = 'export_system_' . $this->getSystem();

		if ($this->getServiceLocator()->has($serviceName)) {
			/** @var System $system */
			$system = $this->getServiceLocator()->get($serviceName);

			$system->setViewCode($this->getView());
			$system->setOptions($this->getOptions());
			$system->setResponse($this->getResponse());
			$system->init();

			try {
				$result = $system->getData($this->getFormat());
			} catch (\Exception $e) {
				throw $e; // Currently just throw again
			}
		} else {
			throw new \Exception('Unknown export service "' . $this->getSystem() . '"');
		}

		return $result;
	}



	/**
	 * Get system
	 *
	 * @return    String
	 */
	protected function getSystem()
	{
		return strtolower($this->params()->fromRoute('system'));
	}



	/**
	 * Get format
	 *
	 * @return    String
	 */
	protected function getFormat()
	{
		return strtolower($this->params()->fromRoute('format'));
	}



	/**
	 * Get view
	 *
	 * @return    String
	 */
	protected function getView()
	{
		return strtolower($this->params()->fromRoute('view'));
	}



	/**
	 * Get options
	 *
	 * @return    Array
	 */
	protected function getOptions()
	{
		$options = $this->params()->fromQuery('option', array());

		if (!is_array($options)) {
			$options = array(
				'default' => $options
			);
		}

		return $options;
	}
}
