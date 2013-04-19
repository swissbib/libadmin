<?php
namespace Libadmin\Table;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Adapter\Adapter;

use Libadmin\Model\BaseModel;

/**
 * [Description]
 *
 */
abstract class BaseTable
{

	/**
	 * @var    String[]    Fulltext search fields
	 */
	protected $searchFields = array();

	protected $tableGateway;



	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}



	/**
	 *
	 *
	 * @param    String        $searchString
	 * @return    PredicateSet
	 */
	public function getSearchFieldsLikeCondition($searchString)
	{
		$searchString = trim($searchString);
		$searchWords = explode(' ', $searchString);
		$searchWords = array_map('trim', $searchWords);
		$predicateSet = new PredicateSet();

		foreach ($searchWords as $searchWord) {
			$likeWhere = new Where(null, Where::COMBINED_BY_OR);

			foreach ($this->searchFields as $searchField) {
				$likeWhere->like($searchField, '%' . $searchWord . '%');
			}

			$predicateSet->addPredicate($likeWhere);
		}

		return $predicateSet;
	}



	/**
	 * Find records
	 *
	 * @param    String            $searchString
	 * @param    String            $order
	 * @param    Integer            $limit
	 * @return    ResultSet
	 */
	protected function findFulltext($searchString, $order, $limit = 30)
	{
		$select = new Select();
		$likeCondition = $this->getSearchFieldsLikeCondition($searchString);

		$select->from($this->getTable())
				->order($order)
				->limit($limit)
				->where($likeCondition);

//		$sql = new Sql($this->tableGateway->getAdapter(), $this->getTable());
//		var_dump($sql->getSqlStringForSqlObject($select));

		return $this->tableGateway->selectWith($select);
	}



	/**
	 * Get table name (shortcut)
	 *
	 * @return    String
	 */
	protected function getTable()
	{
		return $this->tableGateway->getTable();
	}



	/**
	 * Find elements
	 *
	 * @param    String        $searchString
	 * @param    Integer        $limit
	 * @return    BaseModel[]
	 */
	abstract public function find($searchString, $limit = 30);



	/**
	 *
	 * @param    Integer        $idRecord
	 * @return    BaseModel
	 * @throws    \Exception
	 */
	public function getRecord($idRecord)
	{
		$record = $this->tableGateway->select(array('id' => $idRecord))->current();

		if (!$record) {
			throw new \Exception("Could not find record $idRecord in table " . $this->getTable());
		}

		return $record;
	}



	/**
	 * @param    BaseModel    $record
	 * @return    Integer        (New) object ID
	 * @throws    \Exception
	 */
	public function save(BaseModel $record)
	{
		$idRecord = $record->getID();
		$data = $record->getBaseData();

		if ($idRecord == 0) {
			$numRows = $this->tableGateway->insert($data);

			if ($numRows == 1) {
				$idRecord = $this->tableGateway->getLastInsertValue();
			}
		} else {
			if ($this->getRecord($idRecord)) {
				$this->tableGateway->update($data, array('id' => $idRecord));
			} else {
				throw new \Exception(get_class($record) . ' [' . $idRecord . '] does not exist');
			}
		}

		return $idRecord;
	}



	/**
	 * Delete record
	 *
	 * @param    Integer        $idRecord
	 */
	public function delete($idRecord)
	{
		$this->tableGateway->delete(array('id' => $idRecord));
	}



	/**
	 * Get all records from table
	 *
	 * @param    String        $order
	 * @param    Integer        $limit
	 * @return    ResultSetInterface
	 */
	protected function getAll($order, $limit = 30)
	{
		$select = new Select();
		$select->from($this->getTable())
				->order($order);

		if ($limit) {
			$select->limit($limit);
		}

		return $this->tableGateway->selectWith($select);
	}



	/**
	 * Delete group view relation
	 *
	 * @param    Integer        $idGroup
	 * @param    Integer        $idView
	 */
	protected function deleteGroupViewRelation($idGroup, $idView)
	{
		/** @var Adapter $adapter */
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$delete = $sql->delete('mm_group_view');

		$delete->where(array(
			'id_group' => $idGroup,
			'id_view' => $idView
		));

		$query = $sql->getSqlStringForSqlObject($delete);

		//		var_dump($sql->getSqlStringForSqlObject($select));

		$adapter->query($query, $adapter::QUERY_MODE_EXECUTE);
	}



	/**
	 * Add a group-view relation
	 *
	 * @param    Integer        $idGroup
	 * @param    Integer        $idView
	 */
	protected function addGroupViewRelation($idGroup, $idView)
	{
		/** @var Adapter $adapter */
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$insert = $sql->insert('mm_group_view');

		$insert->values(array(
			'id_group' => $idGroup,
			'id_view' => $idView
		));

		$query = $sql->getSqlStringForSqlObject($insert);
		$adapter->query($query, $adapter::QUERY_MODE_EXECUTE);
	}



	/**
	 * Get view IDs for relation
	 *
	 * @param	String		$column
	 * @param	Array		$where
	 * @return	Integer[]
	 */
	protected function getGroupViewRelationIDs($column, array $where)
	{
		return $this->getRelationIDs($column, 'mm_group_view', $where);
	}


	protected function getInstitutionRelationIDs($column, array $where)
	{
		return $this->getRelationIDs($column, 'mm_institution_group_view', $where);
	}



	/**
	 * @param $idGroup
	 * @param $idView
	 * @return \Integer[]
	 */
	public function getRelationInstitutionIDs($idGroup, $idView)
	{
		return $this->getRelationIDs('id_institution', 'mm_institution_group_view', array(
																						 'id_view'	=> (int)$idView,
																						 'id_group'	=> (int)$idGroup
																					));
	}



	/**
	 * Get relation IDs
	 *
	 * @param	String		$column
	 * @param	String		$table
	 * @param	Array		$where
	 * @return	Integer[]
	 */
	protected function getRelationIDs($column, $table, $where)
	{
		/** @var Adapter $adapter */
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->select();

		$select->columns(array($column))
				->from($table)
				->where($where);

		$selectString = $sql->getSqlStringForSqlObject($select);

//		var_dump($sql->getSqlStringForSqlObject($select));

		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		$recordIDs = array();

		foreach ($results as $result) {
			$recordIDs[] = (int)$result[$column];
		}

		return $recordIDs;
	}
}
