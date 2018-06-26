<?php
namespace Libadmin\Export\System;

use Libadmin\Table\TablePluginManager;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\JsonModel;
use Zend\Http\Response as HttpResponse;
use Zend\ServiceManager\ServiceLocatorInterface;

use Libadmin\Model\View;
use Libadmin\Table\GroupTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\ViewTable;
use Interop\Container\ContainerInterface;
use Libadmin\Model\Institution;

/**
 * [Description]
 *
 */
class System
{

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
     * @var TablePluginManager
     */
	protected $tablePluginManager;

	/**
	 * Set service locator
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}


	public function __construct(ContainerInterface $container)
    {
        $this->serviceLocator = $container;
        $this->tablePluginManager = $this->getServiceLocator()->get(TablePluginManager::class);
    }


    /**
	 * Get service locator
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}



	public function init()
	{
		$this->institutionTable = $this->tablePluginManager->get('Libadmin\Table\InstitutionTable');
		$this->groupTable = $this->tablePluginManager->get('Libadmin\Table\GroupTable');
		$this->viewTable = $this->tablePluginManager->get('Libadmin\Table\ViewTable');
	}



	/**
	 * Set view
	 *
	 * @param    String        $view
	 */
	public function setViewCode($view)
	{
		$this->viewCode = $view;
	}



	/**
	 * Get view
	 *
	 * @return String
	 */
	public function getViewCode()
	{
		return $this->viewCode;
	}



	/**
	 * Get view object
	 *
	 * @throws    \Exception
	 * @return    View|null
	 */
	protected function getView()
	{
		if ($this->view === null && !empty($this->viewCode) && $this->viewTable !== null) {
			$view = $this->viewTable->getViewByCode($this->viewCode);

			if (!$view) {
				throw new \Exception('Unknown view "' . $this->viewCode . '"');
			}

			$this->view = $view;
		}

		return $this->view;
	}



	/**
	 * Set options
	 *
	 * @param    Array    $options
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
	}



	/**
	 * Get options
	 *
	 * @return    Array
	 */
	public function getOptions()
	{
		return $this->options;
	}



	/**
	 * Get option
	 *
	 * @param    String        $name
	 * @param    Mixed        $default
	 * @return    Mixed
	 */
	public function getOption($name, $default = null)
	{
		return isset($this->options[$name]) ? $this->options[$name] : $default;
	}



	/**
	 * Set response
	 * Current response object to set custom headers
	 *
	 * @param    ResponseInterface    $response
	 */
	public function setResponse($response)
	{
		$this->response = $response;
	}



	/**
	 * Get data for response
	 *
	 * @param    String        $format
	 * @return    Mixed
	 * @throws    \Exception
	 */
	public function getData($format)
	{
		$method = 'get' . ucfirst($format) . 'Data';

		if (!method_exists($this, $method)) {
			throw new \Exception('Unknown export format "' . $format . '"');
		}

		return $this->$method();
	}



	/**
	 * Get data as json (model)
	 *
	 * @throws \Exception
	 * @return    JsonModel
	 */
	public function getJsonData()
	{
		throw new \Exception('Json export is not supported for system "' . get_class($this) . '"');
	}



	/**
	 * Get data as xml string
	 *
	 * @throws \Exception
	 * @return    String
	 */
	public function getXmlData()
	{
		throw new \Exception('Xml export is not supported for system "' . get_class($this) . '"');
	}

    /**
     * Extract address information from institution
     * If no address is available, return empty values
     *
     * @param    Institution        $institution
     * @return   array
     */
    protected function extractAddressData(Institution $institution)
    {
        //todo : not really efficient, would be better to make a join in the initial query
        $institutionWithAddress = $this->institutionTable->getRecord($institution->getId());
        if($institutionWithAddress->getId_postadresse()) {
            $postAdresse = $institutionWithAddress->getPostadresse();
            $addressAndNumber = $postAdresse->getStrasse();
            if ($postAdresse->getNummer())
            {
                $addressAndNumber .= ' ' . $postAdresse->getNummer();
            }
            return [
                'address'	        => $addressAndNumber,
                'zip'       		=> $postAdresse->getPlz(),
                'city'		        => $postAdresse->getOrt(),
            ];
        } else {
            return [
                'address'	        => '',
                'zip'		        => '',
                'city'		        => '',
            ];
        }
    }

    /**
     * Extract full address information from institution
     * With Canton and Country
     * If no address is available, return empty values
     *
     * @param    Institution        $institution
     * @return   array
     */
    protected function extractFullAddressData(Institution $institution)
    {
        //todo : not really efficient, would be better to make a join in the initial query
        $institutionWithAddress = $this->institutionTable->getRecord($institution->getId());
        if($institutionWithAddress->getId_postadresse()) {
            $postAdresse = $institutionWithAddress->getPostadresse();

            $addressAndNumber = $postAdresse->getStrasse();
            if ($postAdresse->getNummer())
            {
                $addressAndNumber .= ' ' . $postAdresse->getNummer();
            }
            return [
                'address'	        => $addressAndNumber,
                'zip'       		=> $postAdresse->getPlz(),
                'city'		        => $postAdresse->getOrt(),
                'canton'	        => $postAdresse->getCanton(),
                'country'	        => $postAdresse->getCountry(),
            ];
        } else {
            return [
                'address'	        => '',
                'zip'		        => '',
                'city'		        => '',
                'canton'	        => '',
                'country'	        => '',
            ];
        }
    }
}
