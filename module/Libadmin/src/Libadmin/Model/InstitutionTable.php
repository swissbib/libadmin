<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/13/12
 * Time: 2:59 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Libadmin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class InstitutionTable {

	protected $tableGateway;



	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}



	public function getAll($limit = 30) {
		$select = new Select();
		$select->from($this->tableGateway->getTable())
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
		$idInstitution = (int)$institution->id;

		$data = array(
			'bib_code'	=> $institution->bib_code,
			'label_de'  => $institution->label_de
		);

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
