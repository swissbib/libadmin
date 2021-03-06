<?php

/**
 * AdminInstitutionTable
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 29.03.18
 * Time: 11:09
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
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSetInterface;


/**
 * AdminInstitutionTable
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Table
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class AdminInstitutionTable extends InstitutionBaseTable
{


    /**
     * @var    String[]    Fulltext search fields
     */
    protected $searchFields = [
        'name',
        'idcode'
    ];


    /**
     * Initialize with base and relation table
     *
     * @param TableGateway       $adminInstitutionTableGateway adminInstitutionTableGateway
     * @param AdresseTable       $adresseTable                 adresseTable
     * @param KontaktTable       $kontaktTable                 kontaktTable
     * @param KostenbeitragTable $kostenbeitragTable           kostenbeitragTable
     */
    public function __construct(
        TableGateway $adminInstitutionTableGateway,
        AdresseTable $adresseTable,
        KontaktTable $kontaktTable,
        KostenbeitragTable $kostenbeitragTable
    ) {
        parent::__construct($adminInstitutionTableGateway, $adresseTable, $kontaktTable, $kostenbeitragTable);
    }


    //todo: $order parameter is not part of the parent signature
    public function find($searchString, $limit = 30, $order = 'name')
    {
        return $this->findFulltext($searchString, $order, $limit);

    }


    /**
     * Get all institutions
     *
     * @param   String        	$order
     * @param   Integer        	$limit
     * @return	ResultSetInterface
     */
    public function getAll($order = 'name', $limit = 30)
    {
        return parent::getAll($order, $limit);
    }

    /**
     * only save what needs to go to the adminInstitution table, not the rest
     *
     * @param AdminInstitution $adminInstitution
     * @return int
     * @throws \Exception
     * @uses
     */
    public function saveAdminInstitutionOnly(AdminInstitution $adminInstitution) {
        $idInstitution = BaseTable::save($adminInstitution);
        return $idInstitution;
    }

    public function getAllAdminInstitutionsOptions()
    {
        $results = $this->getAll('name', 100);

        return $this->toList($results);
    }

}