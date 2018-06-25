<?php

/**
 * AdminInstitution
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 29.03.18
 * Time: 11:28
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
 * @package  Libadmin_Model
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */

namespace Libadmin\Model;


/**
 * AdminInstitution
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Model
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class AdminInstitution extends InstitutionBase
{
    /*
     * primary Object key
     * @var integer $id
     */
    public $id;

    /**
     * Name of the Institution
     * @var ?String
     */
    public $name;


    /**
     * todo: Silvia fragen
     * unique code for admininstitution ???
     * @var ?String
     */
    public $idcode;





    /**
     * @var ?String
     */
    public $ipadresse;




    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return AdminInstitution
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getIpadresse()
    {
        return $this->ipadresse;
    }

    /**
     * @param mixed $ipadresse
     *
     * @return AdminInstitution
     */
    public function setIpadresse($ipadresse)
    {
        $this->ipadresse = $ipadresse;
        return $this;
    }



    /**
     * Get list label
     *
     * @return    String
     */
    public function getListLabel()
    {
        return $this->getName();
    }

    /**
     * Get type label
     *
     * @return    String
     */
    public function getTypeLabel()
    {
        return 'admininstitution';
    }



    /**
     * @return mixed
     */
    public function getIdcode()
    {
        return $this->idcode;
    }

    /**
     * @param mixed $idcode
     *
     * @return AdminInstitution
     */
    public function setIdcode($idcode)
    {
        $this->idcode = $idcode;
        return $this;
    }





    public function initLocalVariablesFromExcel(array $excelData) {


        $this->setBemerkung_kostenbeitrag($excelData["bemerkung_kostenbeitrag"]);
        $this->setBemerkung_rechnung($excelData["bemerkung_rechnungsstellung"]);
        $this->setBfscode($excelData["bfs_code"]);
        if (!empty($excelData["e_rechnung_ja_nein"])) {
            $this->setE_Rechnung($excelData["e_rechnung_ja_nein"]);
        }
        if (!empty($excelData["mwst_ja_nein"])) {
            $this->setGrund_mwst_frei($excelData["mwst_ja_nein"]);
        }
        $this->setGrund_mwst_frei($excelData["grund_mwst_befreiung"]); //habe ich hier keine MWST
        $this->setKorrespondenzsprache($excelData["korrespondenzsprache"]);
        $this->setZusage_beitrag($excelData["zusage_kostenbeitrag_ja_nein"]);
        $this->setAdresse_rechnung_gleich_post($excelData["rechnungsadresse_gleich_postadresse_ja_nein"]);

        $this->setKostenbeitrag_basiert_auf($this->formatKostenbeitragAdminBasiertAuf( $excelData["kostenbeitrag_basiert_auf"]));
        $this->setIpadresse($excelData["ipadresse"]);
        $this->setIdcode($excelData["idcode"]);
        $this->setName($excelData["name"]);
        $this->setEmail($excelData["mail"]);


    }


}