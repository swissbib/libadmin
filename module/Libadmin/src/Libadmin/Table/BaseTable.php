<?php
namespace Libadmin\Table;

use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSetInterface;

use Libadmin\Model\BaseModel;


/**
 * [Description]
 *
 */
abstract class BaseTable {

	protected $searchFields = array();

	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}



	/**
	 *
	 *
	 * @param	String		$searchString
	 * @return	PredicateSet
	 */
	public function getSearchFieldsLikeCondition($searchString) {
		$searchString	= trim($searchString);
		$searchWords	= explode(' ', $searchString);
		$searchWords	= array_map('trim', $searchWords);
		$predicateSet	= new PredicateSet();

		foreach($searchWords as $searchWord) {
			$likeWhere = new Where(null, Where::COMBINED_BY_OR);

			foreach($this->searchFields as $searchField) {
				$likeWhere->like($searchField, '%' . $searchWord . '%');
			}

			$predicateSet->addPredicate($likeWhere);
		}

		return $predicateSet;
	}



	protected function getTable() {
		return $this->tableGateway->getTable();
	}



	/**
	 * Find elements
	 *
	 * @param	String		$searchString
	 * @param	Integer		$limit
	 * @return	BaseModel[]
	 */
	abstract public function find($searchString, $limit = 30);



	/**
	 *
	 * @param	Integer		$idRecord
	 * @return	BaseModel
	 * @throws	\Exception
	 */
	public function getRecord($idRecord) {
		$record = $this->tableGateway->select(array('id' => $idRecord))->current();

		if( !$record ) {
			throw new \Exception("Could not find record $idRecord in table " . $this->getTable());
		}

		return $record;
	}



	/**
	 * @param	BaseModel	$record
	 * @return	Integer		(New) object ID
	 * @throws	\Exception
	 */
	public function save(BaseModel $record) {
		$idRecord	= $record->getID();
		$data		= $record->getData();

		if( $idRecord == 0 ) {
			$numRows	= $this->tableGateway->insert($data);

			if( $numRows == 1 ) {
				$idRecord = $this->tableGateway->getLastInsertValue();
			}
		}
		else {
			if( $this->getRecord($idRecord) ) {
				$this->tableGateway->update($data, array('id' => $idRecord));
			}
			else {
				throw new \Exception(get_class($record) . ' [' . $idRecord . '] does not exist');
			}
		}

		return $idRecord;
	}



	/**
	 * Delete record
	 *
	 * @param	Integer		$idRecord
	 */
	public function delete($idRecord) {
		$this->tableGateway->delete(array('id' => $idRecord));
	}


	/**
	 * Get all records from table
	 *
	 * @param	String		$order
	 * @param	Integer		$limit
	 * @return	ResultSetInterface
	 */
	protected function getAll($order, $limit = 30) {
		$select = new Select();
		$select->from($this->getTable())
				->order($order)
				->limit($limit);

		return $this->tableGateway->selectWith($select);
	}

}

?>