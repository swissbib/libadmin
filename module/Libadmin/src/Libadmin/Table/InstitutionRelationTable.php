<?php
namespace Libadmin\Table;

use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;

use Libadmin\Model\InstitutionRelation;

/**
 * Relation table for institution-group-view
 *
 */
class InstitutionRelationTable extends BaseTable
{

	/**
	 * Does nothing!
	 *
	 * @param    String        $searchString
	 * @param    Integer        $limit
	 * @return    Array
	 */
	public function find($searchString, $limit = 30)
	{
		return array();
	}



	/**
	 *
	 * @param    InstitutionRelation        $relation
	 * @return    Boolean
	 */
	public function add(InstitutionRelation $relation)
	{
		return $this->tableGateway->insert(array(
			'id_institution' => $relation->getIdInstitution(),
			'id_group' => $relation->getIdGroup(),
			'id_view' => $relation->getIdView(),
			'is_favorite' => $relation->getIsFavorite(),
			'position' => 0 // @todo implement to append to the lsit
		)) == 1;
	}



	/**
	 * Remove all relations for an institution
	 *
	 * @param    Integer        $idInstitution
	 * @return    Boolean
	 */
	public function clear($idInstitution)
	{
		return $this->tableGateway->delete(array(
			'id_institution' => (int)$idInstitution
		)) > 0;
	}



	/**
	 * Get relations for an institution
	 *
	 * @param    Integer        $idInstitution
	 * @return    ResultSet
	 */
	public function getInstitutionRelations($idInstitution)
	{
		$results = $this->tableGateway->select(array(
			'id_institution' => (int)$idInstitution
		));

		$relations = array();

		foreach ($results as $result) {
			$relations[] = $result;
		}

		return $relations;
	}



	/**
	 * Get relations for group and view combination
	 *
	 * @param	Integer		$idGroup
	 * @param	Integer		$idView
	 * @return	InstitutionRelation[]
	 */
	public function getGroupViewRelations($idGroup, $idView)
	{
		$results = $this->tableGateway->select(array(
			'id_group'	=> (int)$idGroup,
			'id_view'	=> (int)$idView
		));

		$relations = array();

		foreach ($results as $result) {
			$relations[] = $result;
		}

		return $relations;
	}



	/**
	 * Remove a specific relation
	 *
	 * @param	Integer		$idInstitution
	 * @param	Integer		$idGroup
	 * @param	Integer		$idView
	 * @return	Boolean
	 */
	public function removeRelation($idInstitution, $idGroup, $idView)
	{
		return $this->tableGateway->delete(array(
										'id_institution'	=> (int)$idInstitution,
										'id_group'			=> (int)$idGroup,
										'id_view'			=> (int)$idView
									)) === 1;
	}

}
