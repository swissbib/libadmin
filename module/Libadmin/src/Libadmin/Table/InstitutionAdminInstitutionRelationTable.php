<?php

/**
 * InstitutionAdminInstitutionRelationTable
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 25.04.18
 * Time: 17:53
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Table
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */

namespace Libadmin\Table;
use Libadmin\Model\AdminInstitution;
use Libadmin\Model\InstitutionAdminInstitutionRelation;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

/**
 * InstitutionAdminInstitutionRelationTable
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Table
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class InstitutionAdminInstitutionRelationTable extends BaseTable
{

    /**
     * Does nothing actually
     *
     * @param    String        $searchString
     * @param    Integer        $limit
     * @return    array
     */
    public function find($searchString, $limit = 30)
    {
        return [];
    }



    /**
     * @param    BaseModel    $record
     * @return    Integer        (New) object ID
     * @throws    \Exception
     */
    public function insertRelation(InstitutionAdminInstitutionRelation $record)
    {
        $data = $record->getBaseData();

        $numRows = $this->tableGateway->insert($data);


    }



    /**
     * Delete record
     *
     * @param    Integer        $idInstitution
     */
    public function deleteWithIdInstitution($idInstitution)
    {
        $this->tableGateway->delete(['id_institution' => $idInstitution]);
    }



    /**
     * Delete record
     *
     * @param    Integer        $idAdminInstitution
     */
    public function deleteWithIdAdmin($idAdminInstitution)
    {
        $this->tableGateway->delete(['id_admininstitution' => $idAdminInstitution]);
    }

    /**
     * Get admin institution id
     *
     * @param   Integer        $idAdminInstitution
     * @param   String         $order
     * @return    null|ResultSetInterface
     */
    public function getAdminInstitutionID($idInstitution)
    {
        /** @var Adapter $adapter */
        $adapter = $this->tableGateway->getAdapter();

        $sql    = new Sql($adapter);
        $select = $sql->select();
        $select->from($this->getTable());
        $select->where(['id_institution' => (int)$idInstitution]);

        $selectString = $sql->buildSqlString($select);
        try {
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if (sizeof($results)>0) {
            return (int)$results[0]['id_admininstitution'];
        } else {
            return null;
        }

    }




}