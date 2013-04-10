<?php
namespace Libadmin\Table;

use Libadmin\Model\InstitutionRelation;
use Zend\Db\Sql\Delete;

/**
 * [Description]
 *
 */
class InstitutionRelationTable extends BaseTable {

	/**
	 * Does nothing!
	 *
	 * @param	String		$searchString
	 * @param	Integer		$limit
	 * @return	Array
	 */
	public function find($searchString, $limit = 30) {
		return array();
	}



	/**
	 *
	 * @param	InstitutionRelation		$relation
	 * @return	Boolean
	 */
	public function add(InstitutionRelation $relation) {
		return $this->tableGateway->insert(array(
			'id_institution'=> $relation->getIdInstitution(),
			'id_group'		=> $relation->getIdGroup(),
			'id_view'		=> $relation->getIdView(),
			'is_favorite'	=> $relation->getIsFavorite(),
			'position'		=> 0 // @todo implement to append to the lsit
		)) == 1;
	}


	public function clear($idInstitution) {
		return $this->tableGateway->delete(array(
			'id_institution'	=> (int)$idInstitution
		));
	}



	/**
	 * @param $idInstitution
	 * @return \Zend\Db\ResultSet\ResultSet
	 */
	public function getRelations($idInstitution) {
//		$select 		= new Select();
//		$likeCondition	= $this->getSearchFieldsLikeCondition($searchString);
//
//		$select->from($this->getTable())
//				->order($order)
//				->limit($limit)
//				->where($likeCondition);

		$results = $this->tableGateway->select(array(
			'id_institution'	=> (int)$idInstitution
		));

		$relations	= array();

		foreach($results as $result) {
			$relations[] = $result;
		}

		return $relations;

//		$sql = new Sql($this->tableGateway->getAdapter(), $this->getTable());
//		var_dump($sql->getSqlStringForSqlObject($select));

//		return $this->tableGateway->selectWith($select);
	}

}

?>