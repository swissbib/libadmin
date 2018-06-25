<?php

/**
 * Kontakt
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

/**
 * Kontakt
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Model
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class Kontakt extends BaseModel
{


    /**
     * Primary key entity
     * @var int
     */
    public $id;

    /**
     * @var ?String
     */
    public $name;

    /**
     * @var ?String
     */
    public $vorname;

    /**
     * @var ?String
     */
    public $anrede;

    /**
     * @var ?String
     */
    public $email;

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
     * @return Kontakt
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVorname()
    {
        return $this->vorname;
    }

    /**
     * @param mixed $vorname
     *
     * @return Kontakt
     */
    public function setVorname($vorname)
    {
        $this->vorname = $vorname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnrede()
    {
        return $this->anrede;
    }

    /**
     * @param mixed $anrede
     *
     * @return Kontakt
     */
    public function setAnrede($anrede)
    {
        $this->anrede = $anrede;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     *
     * @return Kontakt
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function initLocalVariablesFirstPersonFromExcel(array $excelData) {
        $this->setName($excelData["kontakt_name"]);
        $this->setVorname($excelData["kontakt_vorname"]);
        if ($excelData["kontakt_anrede"]=='Frau') {
            $this->setAnrede('f');
        }
        if ($excelData["kontakt_anrede"]=='Herr') {
            $this->setAnrede('m');
        }

        $this->setEmail($excelData["kontakt_email"]);
    }

    public function initLocalVariablesBillPersonFromExcel(array $excelData) {
        $this->setName($excelData["kontakt_rechnung_name"]);
        $this->setVorname($excelData["kontakt_rechnung_vorname"]);
        if ($excelData["kontakt_rechnung_anrede"]=='Frau') {
            $this->setAnrede('f');
        }
        if ($excelData["kontakt_rechnung_anrede"]=='Herr') {
            $this->setAnrede('m');
        }
        $this->setEmail($excelData["kontakt_rechnung_mail"]);
    }
}