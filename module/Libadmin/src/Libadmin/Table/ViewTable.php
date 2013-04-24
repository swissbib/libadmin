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

use Libadmin\Helper\DataTransform;
use Libadmin\Table\BaseTable;
use Libadmin\Model\View;

/**
 * Class ViewTable
 * @package Libadmin\Table
 */
class ViewTable extends BaseTable
{

	/**
	 * @var    String[]    Fulltext search fields
	 */
	protected $searchFields = array(
		'code',
		'label',
		'notes'
	);



	/**
	 * Find institutions
	 *
	 * @param    String            $searchString
	 * @param    Integer            $limit
	 * @return    ResultSet
	 */
	public function find($searchString, $limit = 30)
	{
		return $this->findFulltext($searchString, 'label', $limit);
	}



	/**
	 * Get all views
	 *
	 * @param    Integer        $limit
	 * @param    String        $order
	 * @return    ResultSet
	 */
	public function getAll($limit = 30, $order = 'label')
	{
		return parent::getAll($order, $limit);
	}



	/**
	 * Get view
	 *
	 * @param    Integer        $idView
	 * @return    View
	 */
	public function getRecord($idView)
	{
		return parent::getRecord($idView);
	}



	/**
	 * Get view by code
	 *
	 * @param    String        $code
	 * @param    Boolean        $onlyActive
	 * @return    View|null
	 */
	public function getViewByCode($code, $onlyActive = true)
	{
		$conditions = array(
			'code' => $code
		);

		if ($onlyActive) {
			$conditions['is_active'] = 1;
		}

		return $this->tableGateway->select($conditions)->current();
	}



	/**
	 *
	 *
	 * @param    Integer        $idView
	 * @return    Integer[]
	 */
	public function getGroupIDs($idView)
	{
		return $this->getGroupViewRelationIDs('id_group', array(
			'id_view'	=> $idView
		));
	}



	/**
	 * Save with with group relations
	 *
	 * @param	View    	$view
	 * @param	String		$groupIdsSorted
	 * @param	String		$institutionIdsSorted
	 * @return	Integer
	 */
	public function save(View $view, $groupIdsSorted, $institutionIdsSorted)
	{
		$groupIdsSorted			= DataTransform::intExplode($groupIdsSorted);
		$institutionIdsSorted	= DataTransform::intExplode($groupIdsSorted);

		$idView			= parent::save($view);

			// Save groups: add new records, delete old ones that have been removed
		$this->saveGroups($idView, $view->getGroups());

		return $idView;
	}



	/**
	 * Save group relations
	 *
	 * @param    Integer        $idView
	 * @param    Integer[]    $newGroupIDs
	 */
	protected function saveGroups($idView, array $newGroupIDs)
	{
		$oldGroupIDs = $this->getGroupIDs($idView);

		foreach ($newGroupIDs as $newGroupID) {
			if (!in_array($newGroupID, $oldGroupIDs)) {
				$this->addGroupViewRelation($newGroupID, $idView);
			}
		}
		foreach ($oldGroupIDs as $oldGroupID) {
			if (!in_array($oldGroupID, $newGroupIDs)) {
				$this->deleteGroupViewRelation($oldGroupID, $idView);
			}
		}
	}
}
