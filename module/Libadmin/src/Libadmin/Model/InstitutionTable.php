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
//        $data = array(
//            'libraryid' => $institution->libraryid,
//            'contentXML'  => $institution->contentXML,
//        );
//
//        $tablekey = (int)$institution->tablekey;
//        if ($tablekey == 0) {
//            $this->tableGateway->insert($data);
//        } else {
//            if ($this->getLibrary($tablekey)) {
//                $this->tableGateway->update($data, array('tablekey' => $tablekey));
//            } else {
//                throw new \Exception('Form id does not exist');
//            }
//        }
	}

}
