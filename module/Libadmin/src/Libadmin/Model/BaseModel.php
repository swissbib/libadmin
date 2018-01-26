<?php
namespace Libadmin\Model;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\ArrayObject;

/**
 * [Description]
 *
 */
abstract class BaseModel extends ArrayObject
{

	/**
	 * @var InputFilter
	 */
	protected $inputFilter;



	/**
	 * Get all object vars as array
	 *
	 * @return array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}



	/**
	 * Get data for record without relations
	 *
	 * @return array
	 */
	public function getBaseData()
	{
		$data = $this->getArrayCopy();

		unset($data['id']);
		unset($data['inputFilter']);

		return $data;
	}



	public function exchangeArray($data)
	{
		if (is_object($data)) {
			$data = $data->getArrayCopy();
		}

		$this->initLocalVariables($data);
	}



	/**
	 * Get record ID
	 *
	 * @return    Integer
	 */
	public function getId()
	{
		return (int)$this->id;
	}



	public function setId($id)
	{
		$this->id = $id;
	}



	/**
	 * Initialize local variables if present
	 *
	 * @param  array $data
	 */
	protected function initLocalVariables(array $data)
	{
		foreach ($data as $key => $value) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}



	/**
	 * Get list label key
	 *
	 * @return    String
	 */
	public function getListLabel()
	{
		return get_class($this);
	}



	/**
	 * Get type label key
	 *
	 * @return    String
	 */
	public function getTypeLabel()
	{
		return get_class($this);
	}
}
