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
class AdminInstitution extends BaseModel
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
     * reference to adresse
     * @var ?Integer
     */
    public $id_adresse;

    /**
     * @var ?String
     */
    public $mail;

    /**
     * @var ?Integer
     */
    public $id_kontakt;

    /**
     * @var ?String
     */
    public $korrespondezsprache;

    /**
     * @var ?String
     */
    public $bfscode;

    /**
     * @var ?String
     */
    public $ipadresse;

    /**
     * @var ?String
     */
    public $zusage_beitrag;

    /**
     * @var ?Integer
     */
    public $id_kostenbeitrag;

    /**
     * @var ?String
     */
    public $bemerkung_kostenbeitrag;

    /**
     * @var ?boolean
     */
    public $adresse_rechnung_gleich_post;

    /**
     * @var ?Integer
     */
    public $id_rechnungsadresse;

    /**
     * @var ?Integer
     */
    public $id_kontakt_rechnung;

    /**
     * @var boolean
     */
    public $mwst;

    /**
     * @var ?String
     */
    public $grund_mwst_frei;

    /**
     * @var ?boolean
     */
    public $e_rechnung;

    /**
     * @var ?String
     */
    public $bemerkung_rechnung;

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
    public function getIdAdresse()
    {
        return $this->id_adresse;
    }

    /**
     * @param mixed $id_adresse
     *
     * @return AdminInstitution
     */
    public function setIdAdresse($id_adresse)
    {
        $this->id_adresse = $id_adresse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     *
     * @return AdminInstitution
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdKontakt()
    {
        return $this->id_kontakt;
    }

    /**
     * @param mixed $id_kontakt
     *
     * @return AdminInstitution
     */
    public function setIdKontakt($id_kontakt)
    {
        $this->id_kontakt = $id_kontakt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKorrespondezsprache()
    {
        return $this->korrespondezsprache;
    }

    /**
     * @param mixed $korrespondezsprache
     *
     * @return AdminInstitution
     */
    public function setKorrespondezsprache($korrespondezsprache)
    {
        $this->korrespondezsprache = $korrespondezsprache;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBfscode()
    {
        return $this->bfscode;
    }

    /**
     * @param mixed $bfscode
     *
     * @return AdminInstitution
     */
    public function setBfscode($bfscode)
    {
        $this->bfscode = $bfscode;
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
     * @return mixed
     */
    public function getZusageBeitrag()
    {
        return $this->zusage_beitrag;
    }

    /**
     * @param mixed $zusage_beitrag
     *
     * @return AdminInstitution
     */
    public function setZusageBeitrag($zusage_beitrag)
    {
        $this->zusage_beitrag = $zusage_beitrag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdKostenbeitrag()
    {
        return $this->id_kostenbeitrag;
    }

    /**
     * @param mixed $id_kostenbeitrag
     *
     * @return AdminInstitution
     */
    public function setIdKostenbeitrag($id_kostenbeitrag)
    {
        $this->id_kostenbeitrag = $id_kostenbeitrag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBemerkungKostenbeitrag()
    {
        return $this->bemerkung_kostenbeitrag;
    }

    /**
     * @param mixed $bemerkung_kostenbeitrag
     *
     * @return AdminInstitution
     */
    public function setBemerkungKostenbeitrag($bemerkung_kostenbeitrag)
    {
        $this->bemerkung_kostenbeitrag = $bemerkung_kostenbeitrag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdresseRechnungGleichPost()
    {
        return $this->adresse_rechnung_gleich_post;
    }

    /**
     * @param mixed $adresse_rechnung_gleich_post
     *
     * @return AdminInstitution
     */
    public function setAdresseRechnungGleichPost($adresse_rechnung_gleich_post)
    {
        $this->adresse_rechnung_gleich_post = $adresse_rechnung_gleich_post;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdRechnungsadresse()
    {
        return $this->id_rechnungsadresse;
    }

    /**
     * @param mixed $id_rechnungsadresse
     *
     * @return AdminInstitution
     */
    public function setIdRechnungsadresse($id_rechnungsadresse)
    {
        $this->id_rechnungsadresse = $id_rechnungsadresse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdKontaktRechnung()
    {
        return $this->id_kontakt_rechnung;
    }

    /**
     * @param mixed $id_kontakt_rechnung
     *
     * @return AdminInstitution
     */
    public function setIdKontaktRechnung($id_kontakt_rechnung)
    {
        $this->id_kontakt_rechnung = $id_kontakt_rechnung;
        return $this;
    }

    /**
     * @return mixed
     */
    public function isMwst()
    {
        return $this->mwst;
    }

    /**
     * @param bool $mwst
     *
     * @return AdminInstitution
     */
    public function setMwst($mwst)
    {
        $this->mwst = $mwst;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrundMwstFrei()
    {
        return $this->grund_mwst_frei;
    }

    /**
     * @param mixed $grund_mwst_frei
     *
     * @return AdminInstitution
     */
    public function setGrundMwstFrei($grund_mwst_frei)
    {
        $this->grund_mwst_frei = $grund_mwst_frei;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getERechnung()
    {
        return $this->e_rechnung;
    }

    /**
     * @param mixed $e_rechnung
     *
     * @return AdminInstitution
     */
    public function setERechnung($e_rechnung)
    {
        $this->e_rechnung = $e_rechnung;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBemerkungRechnung()
    {
        return $this->bemerkung_rechnung;
    }

    /**
     * @param mixed $bemerkung_rechnung
     *
     * @return AdminInstitution
     */
    public function setBemerkungRechnung($bemerkung_rechnung)
    {
        $this->bemerkung_rechnung = $bemerkung_rechnung;
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




}