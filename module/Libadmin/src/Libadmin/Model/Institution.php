<?php
namespace Libadmin\Model;

use Libadmin\Model\BaseModel;

class Institution extends BaseModel
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

	public $address;

	public $zip;

	public $city;

	public $country;

	public $canton;

	public $website;

	public $email;

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

    public $adresszusatz;

    public $notes_public_de;

    public $notes_public_fr;

    public $notes_public_en;

    public $notes_public_it;

    public $id_kontakt;

    public $korrespondezsprache;

    public $bfscode;

    public $worldcat_ja_nein;

    public $worldcat_symbol;


    public $cbslibrarycode;

    public $verrechnungbeitrag;

    public $zusage_beitrag;

    public $id_kostenbeitrag;

    public $bemerkung_kostenbeitrag;

    public $kostenbeitrag_basiert_auf;

    public $adresse_rechnung_gleich_post;

    public $id_rechnungsadresse;

    public $id_postadresse;

    public $id_kontakt_rechnung;

    public $mwst;

    public $grund_mwst_frei;

    public $e_rechnung;

    public $bemerkung_rechnung;


    public $is_favorite; // This is not an actual record field
	public $relations = [];

	/**
     * @var Kontakt $kontakt kontakt
     */
	public $kontakt;

    /**
     * @var Adresse $rechnungsadresse rechnungsadresse
     */
    public $rechnungsadresse;

    /**
     * @var Adresse $postadresse postadresse
     */
    public $postadresse;



	/**
	 *
	 * @inheritDoc
	 * @return array
	 */
	public function getBaseData()
	{
		$data = parent::getBaseData();

		unset($data['relations']);
		unset($data['is_favorite']);
        unset($data['kontakt']);
        unset($data['rechnungsadresse']);
        unset($data['postadresse']);

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



	public function setAddress($address)
	{
		$this->address = $address;
	}



	public function getAddress()
	{
		return $this->address;
	}



	public function setBib_code($bib_code)
	{
		$this->bib_code = $bib_code;
	}



	public function getBib_code()
	{
		return $this->bib_code;
	}



	public function setCanton($canton)
	{
		$this->canton = $canton;
	}



	public function getCanton()
	{
		return $this->canton;
	}



	public function setCity($city)
	{
		$this->city = $city;
	}



	public function getCity()
	{
		return $this->city;
	}



	public function setCoordinates($coordinates)
	{
		$this->coordinates = $coordinates;
	}



	public function getCoordinates()
	{
		return $this->coordinates;
	}



	public function setCountry($country)
	{
		$this->country = $country;
	}



	public function getCountry()
	{
		return $this->country;
	}



	public function setEmail($email)
	{
		$this->email = $email;
	}



	public function getEmail()
	{
		return $this->email;
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



	public function setZip($zip)
	{
		$this->zip = $zip;
	}



	public function getZip()
	{
		return $this->zip;
	}



	public function setRelations($relations)
	{
		$this->relations = $relations;
	}



	public function getRelations()
	{
		return $this->relations;
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
    public function getBemerkung_rechnung()
    {
        return $this->bemerkung_rechnung;
    }

    /**
     * @param mixed $bemerkung_rechnung
     *
     * @return Institution
     */
    public function setBemerkung_rechnung($bemerkung_rechnung)
    {
        $this->bemerkung_rechnung = $bemerkung_rechnung;
        return $this;
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
    public function getAdresszusatz()
    {
        return $this->adresszusatz;
    }

    /**
     * @param mixed $adresszusatz
     *
     * @return Institution
     */
    public function setAdresszusatz($adresszusatz)
    {
        $this->adresszusatz = $adresszusatz;
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
    public function getId_kontakt()
    {
        return $this->id_kontakt;
    }

    /**
     * @param mixed $id_kontakt
     *
     * @return Institution
     */
    public function setId_kontakt($id_kontakt)
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
     * @return Institution
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
     * @return Institution
     */
    public function setBfscode($bfscode)
    {
        $this->bfscode = $bfscode;
        return $this;
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
    public function getZusage_beitrag()
    {
        return $this->zusage_beitrag;
    }

    /**
     * @param mixed $zusage_beitrag
     *
     * @return Institution
     */
    public function setZusage_beitrag($zusage_beitrag)
    {
        $this->zusage_beitrag = $zusage_beitrag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId_kostenbeitrag()
    {
        return $this->id_kostenbeitrag;
    }

    /**
     * @param mixed $id_kostenbeitrag
     *
     * @return Institution
     */
    public function setId_kostenbeitrag($id_kostenbeitrag)
    {
        $this->id_kostenbeitrag = $id_kostenbeitrag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBemerkung_kostenbeitrag()
    {
        return $this->bemerkung_kostenbeitrag;
    }

    /**
     * @param mixed $bemerkung_kostenbeitrag
     *
     * @return Institution
     */
    public function setBemerkung_kostenbeitrag($bemerkung_kostenbeitrag)
    {
        $this->bemerkung_kostenbeitrag = $bemerkung_kostenbeitrag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdresse_rechnung_gleich_post()
    {
        return $this->adresse_rechnung_gleich_post;
    }

    /**
     * @param mixed $adresse_rechnung_gleich_post
     *
     * @return Institution
     */
    public function setAdresse_rechnung_gleich_post($adresse_rechnung_gleich_post)
    {
        $this->adresse_rechnung_gleich_post = $adresse_rechnung_gleich_post;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId_rechnungsadresse()
    {
        return $this->id_rechnungsadresse;
    }

    /**
     * @param mixed $id_rechnungsadresse
     *
     * @return Institution
     */
    public function setId_rechnungsadresse($id_rechnungsadresse)
    {
        $this->id_rechnungsadresse = $id_rechnungsadresse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId_postadresse()
    {
        return $this->id_postadresse;
    }

    /**
     * @param mixed $id_postadresse
     *
     * @return Institution
     */
    public function setId_postadresse($id_postadresse)
    {
        $this->id_postadresse = $id_postadresse;
        return $this;
    }





    /**
     * @return mixed
     */
    public function getId_kontakt_rechnung()
    {
        return $this->id_kontakt_rechnung;
    }

    /**
     * @param mixed $id_kontakt_rechnung
     *
     * @return Institution
     */
    public function setId_kontakt_rechnung($id_kontakt_rechnung)
    {
        $this->id_kontakt_rechnung = $id_kontakt_rechnung;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMwst()
    {
        return $this->mwst;
    }

    /**
     * @param mixed $mwst
     *
     * @return Institution
     */
    public function setMwst($mwst)
    {
        $this->mwst = $mwst;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrund_mwst_frei()
    {
        return $this->grund_mwst_frei;
    }

    /**
     * @param mixed $grund_mwst_frei
     *
     * @return Institution
     */
    public function setGrund_mwst_frei($grund_mwst_frei)
    {
        $this->grund_mwst_frei = $grund_mwst_frei;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getE_rechnung()
    {
        return $this->e_rechnung;
    }

    /**
     * @param mixed $e_rechnung
     *
     * @return Institution
     */
    public function setE_rechnung($e_rechnung)
    {
        $this->e_rechnung = $e_rechnung;
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
     * @return mixed
     */
    public function setKostenbeitrag_basiert_auf($kostenbeitrag_basiert_auf)
    {
        $this->kostenbeitrag_basiert_auf = $kostenbeitrag_basiert_auf;
    }



    /**
     * @return Kontakt
     */
    public function getKontakt(): Kontakt
    {
        return $this->kontakt;
    }

    /**
     * @param Kontakt $kontakt
     *
     * @return Kontakt
     */
    public function setKontakt($kontakt)
    {
        $this->kontakt = $kontakt;
    }

    /**
     * @return Adresse
     */
    public function getRechnungsadresse(): Adresse
    {
        return $this->rechnungsadresse;
    }

    /**
     * @param Adresse $rechnungsadresse
     *
     * @return Adresse
     */
    public function setRechnungsadresse($rechnungsadresse)
    {
        $this->rechnungsadresse = $rechnungsadresse;
    }

    /**
     * @return Adresse
     */
    public function getPostadresse(): Adresse
    {
        return $this->postadresse;
    }

    /**
     * @param Adresse $postadresse
     *
     * @return Adresse
     */
    public function setPostadresse($postadresse)
    {
        $this->postadresse = $postadresse;
    }

    public function initLocalVariablesFromExcel(array $excelData) {


        $this->setBemerkung_kostenbeitrag($excelData["bemerkung_kostenbeitrag"]);
        $this->setBemerkung_rechnung($excelData["bemerkung_rechnungsstellung"]);
        $this->setBfscode($excelData["bfs_code"]);
        $this->setCbslibrarycode($excelData["cbs_library_code"]);
        empty($excelData["e_rechnung_ja_nein"]) ? $this->setE_rechnung(0) : $this->setE_rechnung(1);
        empty($excelData["mwst_ja_nein"]) ? $this->setMwst(0) : $this->setMwst(1);
        $this->getMwst() == 0 ? $this->setGrund_mwst_frei($excelData["grund_mwst_befreiung"]) : $this->setGrund_mwst_frei("");
        $this->setKorrespondezsprache($excelData["korrespondenzsprache"]);
        empty($excelData["worldcat_ja_nein"]) ? $this->setWorldcat_ja_nein(0): $this->setWorldcat_ja_nein(1);
        //$this->getWorldcat_ja_nein() === true ? $this->setWorldcatSyMmbol($excelData["worldcat_symbol"]) : $this->setWorldcat_symbol("");
        //todo: Frage an Silvia
        //worldcat_symbol ist gesetzt auch wenn worldcat_ja_nein leer ist - richtig?
        $this->setWorldcat_symbol($excelData["worldcat_symbol"]);
        $this->setCoordinates($excelData["koordinaten"]);
        empty($excelData["zusage_kostenbeitrag_ja_nein"]) ? $this->setZusage_beitrag(0) : $this->setZusage_beitrag(1);
        $this->setEmail($excelData["mail"]);
        empty($excelData["rechnungsadresse_gleich_postadresse_ja_nein"]) ||
        strtolower( $excelData["rechnungsadresse_gleich_postadresse_ja_nein"]) === "ja" ? $this->setAdresse_rechnung_gleich_post(1) :
            $this->setAdresse_rechnung_gleich_post(0);
        $this->setKostenbeitrag_basiert_auf($this->formatKostenbeitragBasiertAuf( $excelData["kostenbeitrag_basiert_auf"]));
        $this->setVerrechnungbeitrag($this->formatVerrechnungsbeitrag( $excelData["verrechnung_kostenbeitrag_auswahl"]));



    }







}
