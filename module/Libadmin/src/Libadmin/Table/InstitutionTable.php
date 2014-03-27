<?php
namespace Libadmin\Table;

use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\PredicateSet;

use Libadmin\Table\BaseTable;
use Libadmin\Model\BaseModel;
use Libadmin\Model\Institution;
use Libadmin\Model\InstitutionRelation;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class InstitutionTable
 * @package Libadmin\Table
 */
class InstitutionTable extends BaseTable
{

	/**
	 * @var InstitutionRelationTable
	 */
	protected $relationTable;



	/**
	 * Initialize with base and relation table
	 *
	 * @param    TableGateway                $institutionTableGateway
	 * @param    InstitutionRelationTable    $relationTable
	 */
	public function __construct(TableGateway $institutionTableGateway, InstitutionRelationTable $relationTable)
	{
		parent::__construct($institutionTableGateway);

		$this->relationTable = $relationTable;
	}



	/**
	 * @var    String[]    Fulltext search fields
	 */
	protected $searchFields = array(
		'bib_code',
		'sys_code',
		'label_de',
		'label_fr'
	);



	/**
	 * Find institutions
	 *
	 * @param    String            $searchString
	 * @param    Integer            $limit
	 * @param    String            $order
	 * @return    BaseModel[]
	 */
	public function find($searchString, $limit = 30, $order = 'bib_code')
	{
		return $this->findFulltext($searchString, $order, $limit);
	}



	/**
	 * Get all institutions
	 *
	 * @param   String        	$order
	 * @param   Integer        	$limit
	 * @return	ResultSetInterface
	 */
	public function getAll($order = 'bib_code', $limit = 30)
	{
		return parent::getAll($order, $limit);
	}



	/**
	 * Get institution
	 *
	 * @param    Integer        $idInstitution
	 * @return    Institution
	 */
	public function getRecord($idInstitution)
	{
		return parent::getRecord($idInstitution);
	}



	/**
	 * Get all institutions which are related to the given view
	 *
	 * @param   Integer        $idView
	 * @param   String         $order
	 * @return	null|ResultSetInterface
	 */
	public function getViewInstitutions($idView, $order = 'position')
	{
		$select = new Select($this->getTable());

		$select->columns(array('*'))
			->join(array(
				'mm' => 'mm_institution_group_view'),
				'institution.id = mm.id_institution',
				array('position')
			)
			->where(array(
				'mm.id_view' => (int)$idView
			))
			->order($order)
			->group('institution.id');

//			$sql = new Sql($this->tableGateway->getAdapter(), $this->getTable());
//			var_dump($sql->getSqlStringForSqlObject($select));

		return $this->tableGateway->selectWith($select);
	}



	/**
	 * Save institution
	 *
	 * @param    Institution        $institution
	 * @return    Integer
	 */
	public function save(Institution $institution)
	{
		$relations = $institution->getRelations();
		$idInstitution = parent::save($institution);

		$this->saveRelations($idInstitution, $relations);

		return $idInstitution;
	}



	/**
	 * Save institution relations
	 *
	 * @param    Integer        $idInstitution
	 * @param    InstitutionRelation[]    $relations
	 */
	protected function saveRelations($idInstitution, array $relations)
	{
		$institutionRelations = $this->relationTable->getInstitutionRelations($idInstitution);
		if (!$this->relationsChanged($institutionRelations, $relations)) return;

		$this->relationTable->deleteInstitutionRelations($idInstitution);

		foreach ($relations as $relation) {
			if ($relation->hasView()) {
				$relation->setIdInstitution($idInstitution);
				$this->relationTable->add($relation);
			}
		}
	}



	/**
	 * @param InstitutionRelation[] $oldRelations
	 * @param InstitutionRelation[] $newRelations
	 *
	 * @return bool
	 */
	private function relationsChanged(array $oldRelations, array $newRelations) {
		$filteredNewRelations = array();

		//Filter the views that are not checked
		foreach ( $newRelations as $newRelation ) {
			if ( $newRelation->hasView() ) $filteredNewRelations[$newRelation->getPrimaryKey()] = $newRelation;
		}

		//Check if the numbers of relations match
		if ( count($oldRelations) !== count($filteredNewRelations) ) return true;

		//Check the relations for equality
		foreach ( $oldRelations as $oldRelation ) {
			$newRelation = $filteredNewRelations[$oldRelation->getPrimaryKey()];

			if ( !$oldRelation->equals($newRelation) ) return true;
		}

		return false;
	}



	/**
	 * Get all institutions which are related with a view and group
	 *
	 * @param    Integer        $idView
	 * @param    Integer        $idGroup
	 * @param    Boolean        $activeOnly
     * @param    Boolean        $favoriteOnly
	 * @return    null|ResultSetInterface
	 */
	public function getAllGroupViewInstitutions($idView, $idGroup, $activeOnly = true, $favoriteOnly = false)
	{
		$select = new Select($this->getTable());

		$select->columns(array('*'))
				->join(array(
						'mm' => 'mm_institution_group_view'),
						'institution.id = mm.id_institution',
						array('is_favorite', 'position'),
						$select::JOIN_LEFT
					)
				->where(array(
					'mm.id_view' => (int)$idView,
					'mm.id_group' => (int)$idGroup
				))
				->order('mm.position');

		if ($activeOnly) {
			$select->where(array(
				'institution.is_active' => 1
			));
		}

		if ($favoriteOnly) {
			$select->where(array(
				'mm.is_favorite' => 1
			));
		}

//		$sql = new Sql($this->tableGateway->getAdapter(), $this->getTable());
//		var_dump($sql->getSqlStringForSqlObject($select));

		return $this->tableGateway->selectWith($select);
	}
}
