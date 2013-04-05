<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/13/12
 * Time: 2:59 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Libadmin\Table;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\PredicateSet;

use Libadmin\Table\BaseTable;
use Libadmin\Model\BaseModel;
use Libadmin\Model\View;


class ViewTable extends BaseTable {

	protected $searchFields = array(
		'bib_code',
		'sys_code',
		'label_de',
		'label_fr'
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



	public function getAll($limit = 30) {
		$select = new Select();
		$select->from($this->getTable())
				->order('label_de')
				->limit($limit);

		return $this->tableGateway->selectWith($select);
	}



	public function getInstitution($idInstitution) {
		$institution = $this->tableGateway->select(array('id' => $idInstitution))->current();

		if( !$institution ) {
			throw new \Exception("Could not find institution $idInstitution");
		}

		return $institution;
	}



	public function saveInstitution(Institution $institution) {
		$idInstitution	= $institution->getID();
		$data			= $institution->getData();

		if( $idInstitution == 0 ) {
			$numRows	= $this->tableGateway->insert($data);

			if( $numRows == 1 ) {
				$idInstitution = $this->tableGateway->getLastInsertValue();
			}
		}
		else {
			if( $this->getInstitution($idInstitution) ) {
				$this->tableGateway->update($data, array('id' => $idInstitution));
			}
			else {
				throw new \Exception('Institution does not exist');
			}
		}

		return $idInstitution;
	}

}
