<?php
namespace Libadmin\Table;

use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
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

}

?>