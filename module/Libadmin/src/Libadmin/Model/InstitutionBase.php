<?php
namespace Libadmin\Model;

use Libadmin\Model\BaseModel;

/**
 * InstitutionBase
 *
 * @category Swissbib_Libadmin
 * @author   lionel.walter@unibas.ch
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 *
 * This is a class which is the parent of Institution and AdminInstitution.
 * In this class are attributes and methods which are used by both of these classes.
 */
abstract class InstitutionBase extends BaseModel
{

	public $email;

    public $id_kontakt;

    public $korrespondezsprache;

    public $bfscode;

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

	/**
     * @var Kontakt $kontakt kontakt
     */
	public $kontakt;

    /**
     * @var Kontakt $kontakt_rechung kontakt für rechnung
     */
    public $kontakt_rechnung;



    /**
     * @var Adresse $rechnungsadresse rechnungsadresse
     */
    public $rechnungsadresse;

    /**
     * @var Adresse $postadresse postadresse
     */
    public $postadresse;

    /**
     * @var Kostenbeitrag $kostenbeitrag kostenbeiträge
     */
    public $kostenbeitrag;


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

        unset($data['kontakt']);
        unset($data['kontakt_rechnung']);
        unset($data['rechnungsadresse']);
        unset($data['postadresse']);
        unset($data['kostenbeitrag']);

		return $data;
	}





	public function setEmail($email)
	{
		$this->email = $email;
	}



	public function getEmail()
	{
		return $this->email;
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

    /**
     * @return Kostenbeitrag
     */
    public function getKostenbeitrag(): Kostenbeitrag
    {
        return $this->kostenbeitrag;
    }

    /**
     * @param Kostenbeitrag $kostenbeitrag
     *
     * @return Kostenbeitrag
     */
    public function setKostenbeitrag($kostenbeitrag)
    {
        $this->kostenbeitrag = $kostenbeitrag;
    }

    /**
     * @return Kontakt
     */
    public function getKontakt_rechnung(): Kontakt
    {
        return $this->kontakt_rechnung;
    }

    /**
     * @param Kontakt $kontakt_rechnung
     *
     * @return Kontakt
     */
    public function setKontakt_rechnung($kontakt_rechnung)
    {
        $this->kontakt_rechnung = $kontakt_rechnung;
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
     * @return mixed
     */
    public function setBfscode($bfscode)
    {
        $this->bfscode = $bfscode;
    }

}
