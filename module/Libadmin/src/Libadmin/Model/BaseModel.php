<?php
namespace Libadmin\Model;

use Zend\InputFilter\InputFilter;

/**
 * [Description]
 *
 */
abstract class BaseModel
{

	/** @var  Integer */
	protected $id;


	/**
	 * @var InputFilter
	 */
	protected $inputFilter;



	/**
	 * Get all object vars as array
	 *
	 * @return    Array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}



	/**
	 * Get data for record without relations
	 *
	 * @return    Array
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
	 * @param    Array    $data
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
	abstract public function getListLabel();



	/**
	 * Get type label key
	 *
	 * @return    String
	 */
	abstract public function getTypeLabel();
}
