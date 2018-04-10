<?php
namespace Libadmin\Controller;

use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Controller\AbstractActionController;

use Libadmin\Export\System\System;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\JsonModel;

/**
 * Export api controller
 *
 */
class ApiController extends AbstractActionController
{

    /**
     * @var ServiceManager
     */
    private $serviceManager;

    public function __construct(ContainerInterface $container)
    {
        $this->serviceManager = $container;

    }

    private function getServiceLocator()
    {

        return $this->serviceManager;

    }

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

            //[
            //'status' => 'SUCCESS',
            //'data' => [
            //    'full_name' => 'John Doe',
            //    'address' => '51 Middle st.'
            //]
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
