<?php

/**
 * Kostenbeitrag
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 29.03.18
 * Time: 17:37
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
 * Kostenbeitrag
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Model
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class Kostenbeitrag extends BaseModel
{


    /**
     * primary entit key
     * @var int
     */
    public $id;


    /**
     * @var ?double
     */
    public $j2018;

    /**
     * @var ?double
     */
    public $j2019;

    /**
     * @var ?double
     */
    public $j2020;

    /**
     * @var ?double
     */
    public $j2021;

    /**
     * @var ?double
     */
    public $j2022;

    /**
     * @var ?double
     */
    public $j2023;

    /**
     * @var ?double
     */
    public $j2025;

    /**
     * @var ?double
     */
    public $j2026;

    /**
     * @var ?double
     */
    public $j2027;

    /**
     * @var ?double
     */
    public $j2028;

    /**
     * @var ?double
     */
    public $j2029;

    /**
     * @var ?double
     */
    public $j2030;

    /**
     * @return mixed
     */
    public function getJ2018()
    {
        return $this->j2018;
    }

    /**
     * @param mixed $j2018
     *
     * @return Kostenbeitrag
     */
    public function setJ2018($j2018)
    {
        $this->j2018 = $j2018;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJ2019()
    {
        return $this->j2019;
    }

    /**
     * @param mixed $j2019
     *
     * @return Kostenbeitrag
     */
    public function setJ2019($j2019)
    {
        $this->j2019 = $j2019;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJ2020()
    {
        return $this->j2020;
    }

    /**
     * @param mixed $j2020
     *
     * @return Kostenbeitrag
     */
    public function setJ2020($j2020)
    {
        $this->j2020 = $j2020;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJ2021()
    {
        return $this->j2021;
    }

    /**
     * @param mixed $j2021
     *
     * @return Kostenbeitrag
     */
    public function setJ2021($j2021)
    {
        $this->j2021 = $j2021;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJ2022()
    {
        return $this->j2022;
    }

    /**
     * @param mixed $j2022
     *
     * @return Kostenbeitrag
     */
    public function setJ2022($j2022)
    {
        $this->j2022 = $j2022;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJ2023()
    {
        return $this->j2023;
    }

    /**
     * @param mixed $j2023
     *
     * @return Kostenbeitrag
     */
    public function setJ2023($j2023)
    {
        $this->j2023 = $j2023;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJ2025()
    {
        return $this->j2025;
    }

    /**
     * @param mixed $j2025
     *
     * @return Kostenbeitrag
     */
    public function setJ2025($j2025)
    {
        $this->j2025 = $j2025;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJ2026()
    {
        return $this->j2026;
    }

    /**
     * @param mixed $j2026
     *
     * @return Kostenbeitrag
     */
    public function setJ2026($j2026)
    {
        $this->j2026 = $j2026;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJ2027()
    {
        return $this->j2027;
    }

    /**
     * @param mixed $j2027
     *
     * @return Kostenbeitrag
     */
    public function setJ2027($j2027)
    {
        $this->j2027 = $j2027;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJ2028()
    {
        return $this->j2028;
    }

    /**
     * @param mixed $j2028
     *
     * @return Kostenbeitrag
     */
    public function setJ2028($j2028)
    {
        $this->j2028 = $j2028;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJ2029()
    {
        return $this->j2029;
    }

    /**
     * @param mixed $j2029
     *
     * @return Kostenbeitrag
     */
    public function setJ2029($j2029)
    {
        $this->j2029 = $j2029;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJ2030()
    {
        return $this->j2030;
    }

    /**
     * @param mixed $j2030
     *
     * @return Kostenbeitrag
     */
    public function setJ2030($j2030)
    {
        $this->j2030 = $j2030;
        return $this;
    }

    public function initLocalVariablesFromExcel(array $excelData) {
        $this->setJ2018($excelData["beitrag_2018"]);
        $this->setJ2019($excelData["beitrag_2019"]);
        $this->setJ2020($excelData["beitrag_2020"]);
    }


}