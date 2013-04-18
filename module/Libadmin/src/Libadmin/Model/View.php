<?php
namespace Libadmin\Model;

use Libadmin\Model\BaseModel;

/**
 * Class View
 * @package Libadmin\Model
 */
class View extends BaseModel
{

	public $id;

	public $code;

	public $is_active;

	public $label;

	public $notes;

	public $groups = array();



	/**
	 * Get base data
	 * Remove groups relation
	 *
	 * @return    Array
	 */
	public function getBaseData()
	{
		$data = parent::getBaseData();

		unset($data['groups']);

		return $data;
	}



	/**
	 * Get list label
	 *
	 * @return    String
	 */
	public function getListLabel()
	{
		return $this->code . ': ' . $this->label;
	}



	/**
	 * Get type label
	 *
	 * @return    String
	 */
	public function getTypeLabel()
	{
		return 'view';
	}



	public function getLabel()
	{
		return $this->label;
	}



	public function setCode($code)
	{
		$this->code = $code;
	}



	public function getCode()
	{
		return $this->code;
	}



	public function setGroups($groups)
	{
		$this->groups = $groups;
	}



	public function getGroups()
	{
		return $this->groups;
	}



	public function setIsActive($is_active)
	{
		$this->is_active = $is_active;
	}



	public function getIsActive()
	{
		return $this->is_active;
	}



	public function setNotes($notes)
	{
		$this->notes = $notes;
	}



	public function getNotes()
	{
		return $this->notes;
	}
}
