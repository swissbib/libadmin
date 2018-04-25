<?php

/**
 * ImportExportService
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 25.04.18
 * Time: 17:04
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
 * @package  Administration_Services
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */

namespace Administration\Services;
use Libadmin\Table\AdminInstitutionTable;
use Libadmin\Table\AdresseTable;
use Libadmin\Table\InstitutionAdminInstitutionRelationTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\KontaktTable;
use Libadmin\Table\KostenbeitragTable;

/**
 * ImportExportService
 *
 * @category Swissbib_VuFind2
 * @package  Administration_Services
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class ImportExportService
{


    /**
     * @var InstitutionTable
     */
    private $institutionTable;
    /**
     * @var AdminInstitutionTable
     */
    private $adminInstitutionTable;
    /**
     * @var InstitutionAdminInstitutionRelationTable
     */
    private $institutionAdminInstitutionRelationTable;
    /**
     * @var KontaktTable
     */
    private $kontaktTable;
    /**
     * @var AdresseTable
     */
    private $adresseTable;
    /**
     * @var KostenbeitragTable
     */
    private $kostenbeitragTable;
    /**
     * @var array
     */
    private $config;

    public function __construct(
            InstitutionTable $institutionTable,
            AdminInstitutionTable $adminInstitutionTable,
            InstitutionAdminInstitutionRelationTable $institutionAdminInstitutionRelationTable,
            KontaktTable $kontaktTable,
            AdresseTable $adresseTable,
            KostenbeitragTable $kostenbeitragTable,
            array $config
    )
    {


        $this->institutionTable = $institutionTable;
        $this->adminInstitutionTable = $adminInstitutionTable;
        $this->institutionAdminInstitutionRelationTable = $institutionAdminInstitutionRelationTable;
        $this->kontaktTable = $kontaktTable;
        $this->adresseTable = $adresseTable;
        $this->kostenbeitragTable = $kostenbeitragTable;
        $this->config = $config;
    }

    public function loadExcelData(string $filename)
    {
        $loadfile = $filename;
        //todo: load data

    }


}