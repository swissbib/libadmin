<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/13/12
 * Time: 2:59 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Libadmin\Table;


use Zend\Db\ResultSet\ResultSet;

use Libadmin\Table\BaseTable;
use Libadmin\Model\View;
//use Libadmin\Model\BaseModel;
//use Libadmin\Model\View;


class ViewTable extends BaseTable {

	/**
	 * @var	String[]	Fulltext search fields
	 */
	protected $searchFields = array(
		'code',
		'label',
		'notes'
	);


	/**
	 * Find institutions
	 *
	 * @param	String			$searchString
	 * @param	Integer			$limit
	 * @return	ResultSet
	 */
	public function find($searchString, $limit = 30) {
		return $this->findFulltext($searchString, 'label', $limit);
	}



	/**
	 * @param int $limit
	 * @return	ResultSet
	 */
	public function getAll($limit = 30) {
		return parent::getAll('label', $limit);
	}



	/**
	 * @param	Integer		$idGroup
	 * @return	View
	 */
	public function getRecord($idGroup) {
		return parent::getRecord($idGroup);
	}

	/**
	 *
	 *
	 * @param	Integer		$idView
	 * @return	Integer[]
	 */
	public function getGroupIDs($idView) {
		return $this->getRelatedGroupViewIDs('id_group', 'id_view', $idView);
	}


	public function save(View $view) {
		$idView	= parent::save($view);

		$this->saveGroups($idView, $view->getGroups());

		return $idView;
	}


	/**
	 * Save groups relation
	 *
	 * @param	Integer		$idView
	 * @param	Integer[]	$newGroupIDs
	 */
	protected function saveGroups($idView, array $newGroupIDs) {
		$oldGroupIDs	= $this->getGroupIDs($idView);

		foreach($newGroupIDs as $newGroupID) {
			if( !in_array($newGroupID, $oldGroupIDs) ) {
				$this->addGroupViewRelation($newGroupID, $idView);
			}
		}
		foreach($oldGroupIDs as $oldGroupID) {
			if( !in_array($oldGroupID, $newGroupIDs) ) {
				$this->deleteGroupViewRelation($oldGroupID, $idView);
			}
		}
	}
}
