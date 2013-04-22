<?php
namespace Libadmin\Model;

/**
 * [Description]
 *
 */
class InstitutionRelationList extends BaseModel
{
	protected $relations = array();

	protected $view;


	public function __construct($idView = 0, array $relations = array())
	{
		$this->view		= (int)$idView;
		$this->relations = $relations;
	}


	public function getRelations()
	{
		return $this->relations;
	}


	public function getView()
	{
		return $this->view;
	}

}
