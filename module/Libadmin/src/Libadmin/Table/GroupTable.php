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
		return $this->getRelatedGroupViewIDs('id_view', 'id_group', $idGroup);
	}


	public function save(Group $group) {
		$idGroup	= parent::save($group);

		$this->saveViews($idGroup, $group->getViews());

		return $idGroup;
	}



	/**
	 * Save views relation
	 *
	 * @param	Integer		$idGroup
	 * @param	Integer[]	$newViewIDs
	 */
	protected function saveViews($idGroup, array $newViewIDs) {
		$oldViewIDs	= $this->getViewIDs($idGroup);

		foreach($newViewIDs as $newViewID) {
			if( !in_array($newViewID, $oldViewIDs) ) {
				$this->addGroupViewRelation($idGroup, $newViewID);
			}
		}
		foreach($oldViewIDs as $oldViewID) {
			if( !in_array($oldViewID, $newViewIDs) ) {
				$this->deleteGroupViewRelation($idGroup, $oldViewID);
			}
		}
	}

}
