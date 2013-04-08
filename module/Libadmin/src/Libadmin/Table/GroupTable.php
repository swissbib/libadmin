<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/13/12
 * Time: 2:59 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Libadmin\Table;

use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Adapter\Adapter;

use Libadmin\Table\BaseTable;
use Libadmin\Model\BaseModel;
use Libadmin\Model\Group;

/**
 * Class GroupTable
 * @package Libadmin\Table
 */
class GroupTable extends BaseTable {

	/**
	 * @var	String[]	Fulltext search fields
	 */
	protected $searchFields = array(
		'code',
		'label_de',
		'label_fr',
		'label_it',
		'label_en',
		'notes'
	);



	/**
	 * Find institutions
	 *
	 * @param	String			$searchString
	 * @param	Integer			$limit
	 * @return	BaseModel[]
	 */
	public function find($searchString, $limit = 30) {
		return $this->findFulltext($searchString, 'label_de', $limit);
	}



	/**
	 * Get all groups
	 *
	 * @param	Integer		$limit
	 * @return	ResultSetInterface
	 */
	public function getAll($limit = 30) {
		return parent::getAll('label_de', $limit);
	}



	/**
	 * Get group
	 *
	 * @param	Integer		$idGroup
	 * @return	Group
	 */
	public function getRecord($idGroup) {
		return parent::getRecord($idGroup);
	}



	/**
	 * @param $idGroup
	 * @return	Integer[]
	 */
	public function getViewIDs($idGroup) {
		/** @var Adapter $adapter  */
		$adapter	= $this->tableGateway->getAdapter();
		$sql		= new Sql($adapter);
		$select		= $sql->select();

		$select->columns(array('id_view'))
				->from('mm_group_view')
				->where(array(
					'id_group'	=> $idGroup
				));

		$selectString = $sql->getSqlStringForSqlObject($select);

//		var_dump($sql->getSqlStringForSqlObject($select));

		$results	= $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		$viewIds	= array();

		foreach($results as $result) {
			$viewIds[] = $result['id_view'];
		}

		return $viewIds;
	}


	public function save(Group $group) {
		$idGroup	= parent::save($group);

		$this->saveViews($idGroup, $group->getViews());

		return $idGroup;
	}


	protected function saveViews($idGroup, array $newViewIDs) {
		$oldViewIDs	= $this->getViewIDs($idGroup);

		foreach($newViewIDs as $newViewID) {
			if( !in_array($newViewID, $oldViewIDs) ) {
				$this->addViewRelation($idGroup, $newViewID);
			}
		}
		foreach($oldViewIDs as $oldViewID) {
			if( !in_array($oldViewID, $newViewIDs) ) {
				$this->deleteViewRelation($idGroup, $oldViewID);
			}
		}
	}


	protected function deleteViewRelation($idGroup, $idView) {
		/** @var Adapter $adapter  */
		$adapter	= $this->tableGateway->getAdapter();
		$sql		= new Sql($adapter);
		$delete		= $sql->delete('mm_group_view');

		$delete->where(array(
					'id_group'	=> $idGroup,
					'id_view'	=> $idView
				));

		$query = $sql->getSqlStringForSqlObject($delete);

	//		var_dump($sql->getSqlStringForSqlObject($select));

		$adapter->query($query, $adapter::QUERY_MODE_EXECUTE);
	}

	protected function addViewRelation($idGroup, $idView) {
		/** @var Adapter $adapter  */
		$adapter	= $this->tableGateway->getAdapter();
		$sql		= new Sql($adapter);
		$insert		= $sql->insert('mm_group_view');

		$insert->values(array(
			'id_group'	=> $idGroup,
			'id_view'	=> $idView
		));

		$query = $sql->getSqlStringForSqlObject($insert);
		$adapter->query($query, $adapter::QUERY_MODE_EXECUTE);
	}

}
