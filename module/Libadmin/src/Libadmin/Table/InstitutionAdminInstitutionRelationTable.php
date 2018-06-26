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
     * @param    Integer        $idRecord
     */
    public function delete($idRecord)
    {
        $this->tableGateway->delete(['id_institution' => $idRecord]);
    }




}