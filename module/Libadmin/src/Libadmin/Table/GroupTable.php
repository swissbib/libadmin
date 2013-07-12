<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/13/12
 * Time: 2:59 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Libadmin\Table;

use Libadmin\Model\InstitutionRelation;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Adapter\Adapter;

use Libadmin\Table\BaseTable;
use Libadmin\Model\BaseModel;
use Libadmin\Model\Group;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\ArrayUtils;

/**
 * Class GroupTable
 * @package Libadmin\Table
 */
class GroupTable extends BaseTable
{

	/**
	 * @var    String[]    Fulltext search fields
	 */
	protected $searchFields = array(
		'code',
		'label_de',
		'label_fr',
		'label_it',
		'label_en',
		'notes'
	);


	protected $relationTable;



	/**
	 * Initialize with extra relation table
	 *
	 * @param	TableGateway				$tableGateway
	 * @param	InstitutionRelationTable	$institutionRelationTable
	 */
	public function __construct(TableGateway $tableGateway, InstitutionRelationTable $institutionRelationTable)
	{
		parent::__construct($tableGateway);

		$this->relationTable = $institutionRelationTable;
	}



	/**
	 * Find institutions
	 *
	 * @param    String            $searchString
	 * @param    Integer            $limit
	 * @return    BaseModel[]
	 */
	public function find($searchString, $limit = 30)
	{
		return $this->findFulltext($searchString, 'label_de', $limit);
	}



	/**
	 * Get all groups
	 *
	 * @param    String        $order
	 * @param    Integer       $limit
	 * @return   ResultSetInterface
	 */
	public function getAll($order = 'label_de', $limit = 300)
	{
		return parent::getAll($order, $limit);
	}



	/**
	 * Get group
	 *
	 * @param    Integer        $idGroup
	 * @return    Group
	 */
	public function getRecord($idGroup)
	{
		return parent::getRecord($idGroup);
	}



	/**
	 * Get IDs of views which are related to the group
	 *
	 * @param    Integer        $idGroup
	 * @return    Integer[]
	 */
	public function getViewIDs($idGroup)
	{
		return $this->getGroupViewRelationIDs('id_view', array(
			'id_group'	=> $idGroup
		));
	}



	/**
	 * Get all groups which are related to a view (not via institution)
	 *
	 * @param   Integer        $idView
	 * @param   String         $order
	 * @return	null|ResultSetInterface
	 */
	public function getViewGroups($idView, $order = 'position')
	{
		$select = new Select($this->getTable());

		$select->columns(array('*'))
				->join(array(
						'mm' => 'mm_group_view'),
					    'group.id = mm.id_group',
					  	array('position')
				)
				->where(array(
					'mm.id_view' => (int)$idView
				))
				->order($order)
				->group('group.id');

//		$sql = new Sql($this->tableGateway->getAdapter(), $this->getTable());
//		var_dump($sql->getSqlStringForSqlObject($select));

		return $this->tableGateway->selectWith($select);
	}



	/**
	 * Get all groups which are related to a view over an institution
	 *
	 * @param   Integer        $idView
	 * @param   Boolean        $activeOnly
	 * @param   String         $order
	 * @return	null|ResultSetInterface
	 */
	public function getViewGroupsRelatedViaInstitution($idView, $activeOnly = true, $order = 'group.code')
	{
		$select = new Select($this->getTable());

		$select->columns(array('*'))
				->join(array(
						'mm' => 'mm_institution_group_view'),
						'group.id = mm.id_group',
						array(),
						$select::JOIN_RIGHT
				)
				->where(array(
					'mm.id_view' => (int)$idView
				))
				->order($order)
				->group('group.id');

		if ($activeOnly) {
			$select->join(array('i' => 'institution'), 'mm.id_institution = i.id', array());
			$select->where(array(
				'group.is_active'	=> 1,
				'i.is_active'		=> 1
			));

				// Limit to active linked groups
			$select->join('mm_group_view', 'group.id = mm_group_view.id_group', array());
			$select->where(array(
				'mm_group_view.id_view'	=> $idView
			));
		}

//		$sql = new Sql($this->tableGateway->getAdapter(), $this->getTable());
//		var_dump($sql->getSqlStringForSqlObject($select));
//		exit();

		return $this->tableGateway->selectWith($select);
	}



	/**
	 * Save group
	 * Added special array handling to simplify relation management (caused problems, but may be solved clean later)
	 *
	 * @param	Array   	$groupData
	 * @param	Integer		$idGroup
	 * @return	Integer
	 */
	public function save(array $groupData, $idGroup = 0)
	{
		$group	= new Group();
		$group->exchangeArray($groupData['group']);
		$group->setId($idGroup);

		$idGroup	= parent::save($group);
		$newViewIDs = $group->getViews();
		$relations	= $group->getRelatedInstitutionsByView();

		$this->saveViews($idGroup, $newViewIDs);
		$this->saveInstitutionRelations($idGroup, $relations);

		return $idGroup;
	}



	/**
	 * Save views relation
	 *
	 * @param    Integer        $idGroup
	 * @param    Integer[]    $newViewIDs
	 */
	protected function saveViews($idGroup, array $newViewIDs)
	{
		$oldViewIDs = $this->getViewIDs($idGroup);

		foreach ($newViewIDs as $newViewID) {
			if (!in_array($newViewID, $oldViewIDs)) {
				$this->addGroupViewRelation($idGroup, $newViewID);
			}
		}
		foreach ($oldViewIDs as $oldViewID) {
			if (!in_array($oldViewID, $newViewIDs)) {
				$this->deleteGroupViewRelation($idGroup, $oldViewID);
			}
		}
	}



	/**
	 * Save institution relations
	 *
	 * @param	Integer		$idGroup
	 * @param	Array[]	$relations
	 */
	protected function saveInstitutionRelations($idGroup, array $relations)
	{
		foreach ($relations as $idView => $newInstitutionIDs) {
			$oldInstitutionIDs	= $this->getRelationInstitutionIDs($idGroup, $idView);

				// Add missing
			foreach ($newInstitutionIDs as $newInstitutionID) {
				if (!in_array($newInstitutionID, $oldInstitutionIDs)) {
					$this->addInstitutionRelation($newInstitutionID, $idGroup, $idView);
				}
			}

				// Delete removed
			foreach ($oldInstitutionIDs as $oldInstitutionID) {
				if (!in_array($oldInstitutionID, $newInstitutionIDs)) {
					$this->relationTable->removeRelation($oldInstitutionID, $idGroup, $idView);
				}
			}
		}
	}



	/**
	 * Add a new institution relation
	 *
	 * @param	Integer		$idInstitution
	 * @param	Integer		$idGroup
	 * @param	Integer		$idView
	 */
	protected function addInstitutionRelation($idInstitution, $idGroup, $idView)
	{
		$relation	= new InstitutionRelation();
		$relation->setIdGroup($idGroup);
		$relation->setIdView($idView);
		$relation->setIdInstitution($idInstitution);
		$this->relationTable->add($relation);
	}
}
