<?php

/**
 * Adresse
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 29.03.18
 * Time: 17:36
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

use Libadmin\Table\BaseTable;

/**
 * Adresse
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Model
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class Adresse extends BaseModel
{

    /**
     * primary key
     * @var int
     */
    public $id;

    /**
     * @var ?String
     */
    public $strasse;

    /**
     * @var ?String
     */
    public $nummer;

    /**
     * @var ?String
     */
    public $zusatz;

    /**
     * @var ?String
     */
    public $plz;

    /**
     * @var ?String
     */
    public $ort;

    /**
     * @var ?String
     */
    public $country;


    /**
     * @var ?String
     */
    public $canton;



    /**
     * @return mixed
     */
    public function getStrasse()
    {
        return $this->strasse;
    }

    /**
     * @param mixed $strasse
     *
     * @return Adresse
     */
    public function setStrasse($strasse)
    {
        $this->strasse = $strasse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNummer()
    {
        return $this->nummer;
    }

    /**
     * @param mixed $nummer
     *
     * @return Adresse
     */
    public function setNummer($nummer)
    {
        $this->nummer = $nummer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZusatz()
    {
        return $this->zusatz;
    }

    /**
     * @param mixed $zusatz
     *
     * @return Adresse
     */
    public function setZusatz($zusatz)
    {
        $this->zusatz = $zusatz;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlz()
    {
        return $this->plz;
    }

    /**
     * @param mixed $plz
     *
     * @return Adresse
     */
    public function setPlz($plz)
    {
        $this->plz = $plz;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrt()
    {
        return $this->ort;
    }

    /**
     * @param mixed $ort
     *
     * @return Adresse
     */
    public function setOrt($ort)
    {
        $this->ort = $ort;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     *
     * @return Adresse
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCanton()
    {
        return $this->canton;
    }

    /**
     * @param mixed $canton
     *
     * @return Adresse
     */
    public function setCanton($canton)
    {
        $this->canton = $canton;
        return $this;
    }



    public function initLocalVariablesFromExcel(array $excelData) {

        $this->setCountry($excelData["country"]);
        $this->setOrt($excelData["city"]);
        $this->setPlz($excelData["zip"]);
        $this->setStrasse($excelData["address_strasse"]);
        $this->setNummer($excelData["address_nummer"]);
        $this->setZusatz($excelData["address_zusatz"]);

    }

    public function initLocalVariablesFromExcelRechnungsadresse(array $excelData) {

        $this->setCountry($excelData["rechnungsadresse_country"]);
        $this->setOrt($excelData["rechnungsadresse_ort"]);
        $this->setPlz($excelData["rechnungsadresse_plz"]);
        $this->setStrasse($excelData["rechnungsadresse_strasse"]);
        $this->setNummer($excelData["rechnungsadresse_nummer"]);
        $this->setZusatz($excelData["rechnungsadresse_zusatz"]);

    }



}