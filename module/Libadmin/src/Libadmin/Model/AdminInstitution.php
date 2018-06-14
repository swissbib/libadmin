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
     * todo: Silvia fragen
     * unique code for admininstitution ???
     * @var ?String
     */
    public $idcode;


    /**
     * reference to postadresse
     * @var ?Integer
     */
    public $id_postadresse;

    /**
     * @var ?String
     */
    public $email;

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
     * @var ?String
     */
    public $kostenbeitrag_basiert_auf;


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
    public function getIdPostadresse()
    {
        return $this->id_postadresse;
    }

    /**
     * @param mixed $id_postadresse
     *
     * @return AdminInstitution
     */
    public function setIdPostadresse($id_postadresse)
    {
        $this->id_postadresse = $id_postadresse;
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
     * @param mixed $mail
     *
     * @return AdminInstitution
     */
    public function setEmail($email)
    {
        $this->email = $email;
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

    /**
     * @return mixed
     */
    public function getKostenbeitrag_basiert_auf()
    {
        return $this->kostenbeitrag_basiert_auf;
    }

    /**
     * @param mixed $kostenbeitrag_basiert_auf
     *
     * @return AdminInstitution
     */
    public function setKostenbeitragBasiertAuf($kostenbeitrag_basiert_auf)
    {
        $this->kostenbeitrag_basiert_auf = $kostenbeitrag_basiert_auf;
        return $this;
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


        $this->setBemerkungKostenbeitrag($excelData["bemerkung_kostenbeitrag"]);
        $this->setBemerkungRechnung($excelData["bemerkung_rechnungsstellung"]);
        $this->setBfscode($excelData["bfs_code"]);
        empty($excelData["e_rechnung_ja_nein"]) ? $this->setERechnung(0) : $this->setERechnung(1);
        empty($excelData["mwst_ja_nein"]) ? $this->setMwst(0) : $this->setMwst(1);
        $this->setGrundMwstFrei($excelData["grund_mwst_befreiung"]); //habe ich hier keine MWST
        $this->setKorrespondezsprache($excelData["korrespondenzsprache"]);
        empty($excelData["zusage_kostenbeitrag_ja_nein"]) ? $this->setZusageBeitrag(0) : $this->setZusageBeitrag(1);
        empty($excelData["rechnungsadresse_gleich_postadresse_ja_nein"]) ||
        strtolower( $excelData["rechnungsadresse_gleich_postadresse_ja_nein"]) === "ja" ? $this->setAdresseRechnungGleichPost(1) :
            $this->setAdresseRechnungGleichPost(0);

        $this->setKostenbeitragBasiertAuf($this->formatKostenbeitragAdminBasiertAuf( $excelData["kostenbeitrag_basiert_auf"]));
        $this->setIpadresse($excelData["ipadresse"]);
        $this->setIdcode($excelData["idcode"]);
        $this->setName($excelData["name"]);
        $this->setEmail($excelData["mail"]);


    }


}