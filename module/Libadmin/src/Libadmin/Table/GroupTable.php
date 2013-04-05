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

use Libadmin\Table\BaseTable;
use Libadmin\Model\BaseModel;
use Libadmin\Model\Group;


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
		$select 		= new Select();
		$likeCondition	= $this->getSearchFieldsLikeCondition($searchString);

		$select->from($this->getTable())
				->order('label_de')
				->limit($limit)
				->where($likeCondition);

//		$sql = new Sql($this->tableGateway->getAdapter(), $this->getTable());
//		var_dump($sql->getSqlStringForSqlObject($select));

		return $this->tableGateway->selectWith($select);
	}



	/**
	 * Get all records from table
	 *
	 * @param	Integer		$limit
	 * @return	ResultSetInterface
	 */
	public function getAll($limit = 30) {
		return parent::getAll('label_de', $limit);
	}



	/**
	 * @param	Integer		$idGroup
	 * @return	Group
	 */
	public function getRecord($idGroup) {
		return parent::getRecord($idGroup);
	}



//	public function save(Group $record) {
//		$idGroup	= $record->getID();
//		$data			= $record->getData();
//
//		if( $idGroup == 0 ) {
//			$numRows	= $this->tableGateway->insert($data);
//
//			if( $numRows == 1 ) {
//				$idGroup = $this->tableGateway->getLastInsertValue();
//			}
//		}
//		else {
//			if( $this->getRecord($idGroup) ) {
//				$this->tableGateway->update($data, array('id' => $idGroup));
//			}
//			else {
//				throw new \Exception('Institution does not exist');
//			}
//		}
//
//		return $idGroup;
//	}

}
