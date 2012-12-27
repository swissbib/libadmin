<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/13/12
 * Time: 2:59 PM
 * To change this template use File | Settings | File Templates.
 */

namespace  Libraries\Model;

use Zend\Db\TableGateway\TableGateway;

class LibraryTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getLibrary($libraryid)
    {
        $rowset = $this->tableGateway->select(array('libraryid' => $libraryid));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $libraryid");
        }
        return $row;
    }

    public function saveLibrary(Library $library)
    {
        $data = array(
            'libraryid' => $library->libraryid,
            'contentXML'  => $library->contentXML,
        );

        $tablekey = (int)$library->tablekey;
        if ($tablekey == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getLibrary($tablekey)) {
                $this->tableGateway->update($data, array('tablekey' => $tablekey));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }




}
