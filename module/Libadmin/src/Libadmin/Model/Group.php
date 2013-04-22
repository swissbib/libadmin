<?php
namespace Libadmin\Model;

use Libadmin\Model\BaseModel;

/**
 * Group
 *
 * @package    Libadmin\Model
 */
class Group extends BaseModel
{

	public $id;

	public $code;

	public $is_active;

	public $label_de;

	public $label_fr;

	public $label_it;

	public $label_en;

	public $notes;

	public $views = array();


	public $relations = array();



	public function setRelations($relations)
	{
		$this->relations = $relations;
	}



	public function getRelations()
	{
		return $this->relations;
	}



	/**
	 * Get institutions IDs grouped by views
	 *
	 * @return	Array[]
	 */
	public function getRelatedInstitutionsByView()
	{
		$data	= array();

		if (!is_array($this->relations) || !sizeof($this->relations)) {
			return $data;
		}

		if ($this->relations[0] instanceof InstitutionRelationList) {
			return $data;
		}

		foreach ($this->relations as $relation) {
			$data[$relation['view']] = isset($relation['institutions'])  ? $relation['institutions'] : array();
		}

		return $data;
	}



	/**
	 * Get data for the record (without relation data)
	 *
	 * @return    Array
	 */
	public function getBaseData()
	{
		$data = parent::getBaseData();

		unset($data['views']);
		unset($data['relations']);

		return $data;
	}



	/**
	 * Get listing label
	 *
	 * @return    String
	 */
	public function getListLabel()
	{
		return $this->code . ': ' . $this->label_de;
	}



	/**
	 * Get type label
	 *
	 * @return    String
	 */
	public function getTypeLabel()
	{
		return 'group';
	}



	public function getLabel_de()
	{
		return $this->label_de;
	}



	public function getLabel_en()
	{
		return $this->label_en;
	}



	public function getLabel_fr()
	{
		return $this->label_fr;
	}



	public function getLabel_it()
	{
		return $this->label_it;
	}



	public function getNotes()
	{
		return $this->notes;
	}



	public function getCode()
	{
		return $this->code;
	}



	public function setCode($code)
	{
		$this->code = $code;
	}



	public function getIs_active()
	{
		return $this->is_active;
	}



	public function setIs_active($is_active)
	{
		$this->is_active = $is_active;
	}



	public function setLabel_De($label_de)
	{
		$this->label_de = $label_de;
	}



	public function setLabel_En($label_en)
	{
		$this->label_en = $label_en;
	}



	public function setLabel_Fr($label_fr)
	{
		$this->label_fr = $label_fr;
	}



	public function setLabel_It($label_it)
	{
		$this->label_it = $label_it;
	}



	public function setNotes($notes)
	{
		$this->notes = $notes;
	}



	/**
	 * @return    Integer[]
	 */
	public function getViews()
	{
		return $this->views;
	}



	public function setViews(array $views)
	{
		$this->views = $views;

		return $this;
	}
}
