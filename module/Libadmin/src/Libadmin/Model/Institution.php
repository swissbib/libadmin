<?php
namespace Libadmin\Model;

use Libadmin\Model\BaseModel;

class Institution extends InstitutionBase
{

	public $id;

	public $bib_code;

	public $sys_code;

	public $is_active;

	public $label_de;

	public $label_fr;

	public $label_it;

	public $label_en;

	public $name_de;

	public $name_fr;

	public $name_it;

	public $name_en;

	public $url_de;

	public $url_fr;

	public $url_it;

	public $url_en;

	public $website;

	public $phone;

	public $skype;

	public $facebook;

	public $twitter;

	public $coordinates;

	public $isil;

	public $notes;

	public $url_web_en;

    public $url_web_de;

    public $url_web_fr;

    public $url_web_it;

    public $notes_public_de;

    public $notes_public_fr;

    public $notes_public_en;

    public $notes_public_it;

    public $worldcat_ja_nein;

    public $worldcat_symbol;

    public $cbslibrarycode;

    public $verrechnungbeitrag;

    public $is_favorite; // This is not an actual record field
	public $relations = [];

	public $admin_institution_id;

	/**
     * These are all the fields which are directly stored
     * in the institution table
	 *
	 * @inheritDoc
	 * @return array
	 */
	public function getBaseData()
	{
		$data = parent::getBaseData();

		unset($data['relations']);
		unset($data['is_favorite']);
        unset($data['admin_institution_id']);

		return $data;
	}



	/**
	 * Get list label
	 *
	 * @return    String
	 */
	public function getListLabel()
	{
		return $this->bib_code . ': ' . $this->label_de;
	}



	/**
	 * Get type label
	 *
	 * @return    String
	 */
	public function getTypeLabel()
	{
		return 'institution';
	}



	public function setBib_code($bib_code)
	{
		$this->bib_code = $bib_code;
	}



	public function getBib_code()
	{
		return $this->bib_code;
	}


	public function setCoordinates($coordinates)
	{
		$this->coordinates = $coordinates;
	}



	public function getCoordinates()
	{
		return $this->coordinates;
	}

    /**
     * returns the latitude as a float
     * it is the first part of the coordinates, before the ','
     * throws an exception if the coordinates are absent or if they are in a wrong format
     *
     * @return float
     */
    public function getLatitude()
    {
        $coordinates = explode(',', $this->coordinates);
        if (sizeof($coordinates) != 2 ) {
            throw new \Exception(
                'impossible to extract the latitude from the coordinates ' .
                $this->coordinates
            );
        }
        return floatval(trim($coordinates[0]));
    }

    /**
     * returns the longitude as a float
     * it is the second part of the coordinates, after the ', '
     * throws an exception if the coordinates are absent or if they are in a wrong format
     *
     * @return float
     */
    public function getLongitude()
    {
        $coordinates = explode(',', $this->coordinates);
        if (sizeof($coordinates) != 2 ) {
            throw new \Exception(
                'impossible to extract the longitude from the coordinates ' .
                $this->coordinates
            );
        }
        return floatval(trim($coordinates[1]));
    }

	public function setFacebook($facebook)
	{
		$this->facebook = $facebook;
	}



	public function getFacebook()
	{
		return $this->facebook;
	}



	public function setIs_active($is_active)
	{
		$this->is_active = $is_active;
	}



	public function getIs_active()
	{
		return $this->is_active;
	}



	public function setIsil($isil)
	{
		$this->isil = $isil;
	}



	public function getIsil()
	{
		return $this->isil;
	}



	public function setLabel_de($label_de)
	{
		$this->label_de = $label_de;
	}



	public function getLabel_de()
	{
		return $this->label_de;
	}



	public function setLabel_en($label_en)
	{
		$this->label_en = $label_en;
	}



	public function getLabel_en()
	{
		return $this->label_en;
	}



	public function setLabel_fr($label_fr)
	{
		$this->label_fr = $label_fr;
	}



	public function getLabel_fr()
	{
		return $this->label_fr;
	}



	public function setLabel_it($label_it)
	{
		$this->label_it = $label_it;
	}



	public function getLabel_it()
	{
		return $this->label_it;
	}



	public function setName_de($name_de)
	{
		$this->name_de = $name_de;
	}



	public function getName_de()
	{
		return $this->name_de;
	}



	public function setName_en($name_en)
	{
		$this->name_en = $name_en;
	}



	public function getName_en()
	{
		return $this->name_en;
	}



	public function setName_fr($name_fr)
	{
		$this->name_fr = $name_fr;
	}



	public function getName_fr()
	{
		return $this->name_fr;
	}


	public function setName_it($name_it)
	{
		$this->name_it = $name_it;
	}



	public function getName_it()
	{
		return $this->name_it;
	}



	public function setNotes($notes)
	{
		$this->notes = $notes;
	}



	public function getNotes()
	{
		return $this->notes;
	}



	public function setPhone($phone)
	{
		$this->phone = $phone;
	}



	public function getPhone()
	{
		return $this->phone;
	}



	public function setSkype($skype)
	{
		$this->skype = $skype;
	}



	public function getSkype()
	{
		return $this->skype;
	}



	public function setSys_code($sys_code)
	{
		$this->sys_code = $sys_code;
	}



	public function getSys_code()
	{
		return $this->sys_code;
	}



	public function setUrl_de($url_de)
	{
		$this->url_de = $url_de;
	}



	public function getUrl_de()
	{
		return $this->url_de;
	}



	public function setUrl_en($url_en)
	{
		$this->url_en = $url_en;
	}



	public function getUrl_en()
	{
		return $this->url_en;
	}



	public function setUrl_fr($url_fr)
	{
		$this->url_fr = $url_fr;
	}



	public function getUrl_fr()
	{
		return $this->url_fr;
	}



	public function setUrl_it($url_it)
	{
		$this->url_it = $url_it;
	}



	public function getUrl_it()
	{
		return $this->url_it;
	}



	public function setWebsite($website)
	{
		$this->website = $website;
	}



	public function getWebsite()
	{
		return $this->website;
	}


	public function setRelations($relations)
	{
		$this->relations = $relations;
	}



	public function getRelations()
	{
		return $this->relations;
	}

    /**
     * @return mixed
     */
    public function getAdmin_institution_id()
    {
        return $this->admin_institution_id;
    }

    /**
     * @param mixed $admin_institution_id
     *
     * @return mixed
     */
    public function setAdmin_institution_id($admin_institution_id)
    {
        $this->admin_institution_id = $admin_institution_id;
    }



	public function setIs_favorite($is_favorite)
	{
		$this->is_favorite = $is_favorite;
	}



	public function getIs_favorite()
	{
		return $this->is_favorite;
	}



	public function isFavorite()
	{
		return $this->getIs_favorite() == 1;
	}



    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $twitter
     *
     * @return Institution
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl_web_en()
    {
        return $this->url_web_en;
    }

    /**
     * @param mixed $url_web_en
     *
     * @return Institution
     */
    public function setUrl_web_en($url_web_en)
    {
        $this->url_web_en = $url_web_en;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl_web_de()
    {
        return $this->url_web_de;
    }

    /**
     * @param mixed $url_web_de
     *
     * @return Institution
     */
    public function setUrl_web_de($url_web_de)
    {
        $this->url_web_de = $url_web_de;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl_web_fr()
    {
        return $this->url_web_fr;
    }

    /**
     * @param mixed $url_web_fr
     *
     * @return Institution
     */
    public function setUrl_web_fr($url_web_fr)
    {
        $this->url_web_fr = $url_web_fr;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl_web_it()
    {
        return $this->url_web_it;
    }

    /**
     * @param mixed $url_web_it
     *
     * @return Institution
     */
    public function setUrl_web_it($url_web_it)
    {
        $this->url_web_it = $url_web_it;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getNotes_public_de()
    {
        return $this->notes_public_de;
    }

    /**
     * @param mixed $notes_public_de
     *
     * @return mixed
     */
    public function setNotes_public_de($notes_public_de)
    {
        $this->notes_public_de = $notes_public_de;
    }

    /**
     * @return mixed
     */
    public function getNotes_public_fr()
    {
        return $this->notes_public_fr;
    }

    /**
     * @param mixed $notes_public_fr
     *
     * @return mixed
     */
    public function setNotes_public_fr($notes_public_fr)
    {
        $this->notes_public_fr = $notes_public_fr;
    }

    /**
     * @return mixed
     */
    public function getNotes_public_en()
    {
        return $this->notes_public_en;
    }

    /**
     * @param mixed $notes_public_en
     *
     * @return mixed
     */
    public function setNotes_public_en($notes_public_en)
    {
        $this->notes_public_en = $notes_public_en;
    }

    /**
     * @return mixed
     */
    public function getNotes_public_it()
    {
        return $this->notes_public_it;
    }

    /**
     * @param mixed $notes_public_it
     *
     * @return mixed
     */
    public function setNotes_public_it($notes_public_it)
    {
        $this->notes_public_it = $notes_public_it;
    }





    /**
     * @return mixed
     */
    public function getCbslibrarycode()
    {
        return $this->cbslibrarycode;
    }

    /**
     * @param mixed $cbslibrarycode
     *
     * @return Institution
     */
    public function setCbslibrarycode($cbslibrarycode)
    {
        $this->cbslibrarycode = $cbslibrarycode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVerrechnungbeitrag()
    {
        return $this->verrechnungbeitrag;
    }

    /**
     * @param mixed $verrechnungbeitrag
     *
     * @return Institution
     */
    public function setVerrechnungbeitrag($verrechnungbeitrag)
    {
        $this->verrechnungbeitrag = $verrechnungbeitrag;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getWorldcat_ja_nein()
    {
        return $this->worldcat_ja_nein;
    }

    /**
     * @param mixed $worldcat_ja_nein
     *
     * @return Institution
     */
    public function setWorldcat_ja_nein($worldcat_ja_nein)
    {
        $this->worldcat_ja_nein = $worldcat_ja_nein;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorldcat_symbol()
    {
        return $this->worldcat_symbol;
    }

    /**
     * @param mixed $worldcat_symbol
     *
     * @return Institution
     */
    public function setWorldcat_symbol($worldcat_symbol)
    {
        $this->worldcat_symbol = $worldcat_symbol;
        return $this;
    }



    public function initLocalVariablesFromExcel(array $excelData) {


        $this->setBemerkung_kostenbeitrag($excelData["bemerkung_kostenbeitrag"]);
        $this->setBemerkung_rechnung($excelData["bemerkung_rechnungsstellung"]);
        $this->setBfscode($excelData["bfs_code"]);
        $this->setCbslibrarycode($excelData["cbs_library_code"]);

        $this->setGrund_mwst_frei($excelData["grund_mwst_befreiung"]); //habe ich hier keine MWST
        $this->setKorrespondenzsprache($excelData["korrespondenzsprache"]);

        if (!empty($excelData["worldcat_ja_nein"])) {
            $this->setWorldcat_ja_nein($excelData["worldcat_ja_nein"]);
        }

        if (!empty($excelData["e_rechnung_ja_nein"])) {
            $this->setE_Rechnung($excelData["e_rechnung_ja_nein"]);
        }
        if (!empty($excelData["mwst_ja_nein"])) {
            $this->setMwst($excelData["mwst_ja_nein"]);
        } else {
            $this->setMwst('nein');
        }

        $this->setWorldcat_symbol($excelData["worldcat_symbol"]);
        $this->setCoordinates($excelData["koordinaten"]);
        $this->setZusage_beitrag($excelData["zusage_kostenbeitrag_ja_nein"]);
        $this->setAdresse_rechnung_gleich_post($excelData["rechnungsadresse_gleich_postadresse_ja_nein"]);
        $this->setEmail($excelData["mail"]);
        $this->setKostenbeitrag_basiert_auf($this->formatKostenbeitragBasiertAuf( $excelData["kostenbeitrag_basiert_auf"]));
        $this->setVerrechnungbeitrag($this->formatVerrechnungsbeitrag( $excelData["verrechnung_kostenbeitrag_auswahl"]));



    }







}
