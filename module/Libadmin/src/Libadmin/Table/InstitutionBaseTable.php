<?php
namespace Libadmin\Table;

use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\PredicateSet;

use Libadmin\Table\BaseTable;
use Libadmin\Model\BaseModel;
use Libadmin\Model\Institution;
use Libadmin\Model\Kontakt;
use Libadmin\Model\Adresse;
use Libadmin\Model\Kostenbeitrag;
use Libadmin\Model\InstitutionRelation;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Hydrator\Reflection as ReflectionHydrator;

/**
 * Class InstitutionTable
 * @package Libadmin\Table
 */
abstract class InstitutionBaseTable extends BaseTable
{
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
     * Initialize with base and relation table
     *
     * @param TableGateway             $institutionTableGateway institutionTableGateway
     * @param InstitutionRelationTable $relationTable           relationTable
     * @param AdresseTable             $adresseTable            adresseTable
     * @param KontaktTable             $kontaktTable            kontaktTable
     * @param KostenbeitragTable       $kostenbeitragTable      kostenbeitragTable
     */
    public function __construct(
        TableGateway $institutionTableGateway,
        AdresseTable $adresseTable,
        KontaktTable $kontaktTable,
        KostenbeitragTable $kostenbeitragTable
    ) {
        parent::__construct($institutionTableGateway);

        $this->kontaktTable = $kontaktTable;
        $this->adresseTable = $adresseTable;
        $this->kostenbeitragTable = $kostenbeitragTable;
    }


    /**
     * Get institution
     *
     * @param    Integer        $idInstitution
     * @return    Institution
     */
    public function getRecord($idInstitution)
    {
        /**
         * @var $institution \Libadmin\Model\Institution
         */
        $institution = parent::getRecord($idInstitution);

        $kontakt = $this->loadKontaktFromKontaktTable($institution->getId_kontakt());
        $institution->setKontakt($kontakt);

        $kontakt_rechnung = $this->loadKontaktFromKontaktTable($institution->getId_kontakt_rechnung());
        $institution->setKontakt_rechnung($kontakt_rechnung);

        $rechnungsadresse = $this->loadAdresseFromAdresseTable($institution->getId_rechnungsadresse());
        $institution->setRechnungsadresse($rechnungsadresse);

        $postadresse = $this->loadAdresseFromAdresseTable($institution->getId_postadresse());
        $institution->setPostadresse($postadresse);

        $kostenbeitrag = $this->loadKostenbeitragFromKostenbeitragTable($institution->getId_kostenbeitrag());
        $institution->setKostenbeitrag($kostenbeitrag);

        return $institution;

    }

    /**
     * Save institution
     *
     * @param    BaseModel        $institution
     * @return    Integer
     */
    public function save(BaseModel $institution)
    {
        /**
         * @var $institution \Libadmin\Model\Institution
         */

        $idKontakt=$this->saveKontaktToKontaktTable(
            $institution->getKontakt()
        );
        $institution->setId_kontakt($idKontakt);

        $idKontakt_rechnung=$this->saveKontaktToKontaktTable(
            $institution->getKontakt_rechnung()
        );
        $institution->setId_kontakt_rechnung($idKontakt_rechnung);

        $idRechnungsadresse=$this->saveAdresseToAdresseTable(
            $institution->getRechnungsadresse()
        );
        $institution->setId_rechnungsadresse($idRechnungsadresse);

        $idPostadresse=$this->saveAdresseToAdresseTable(
            $institution->getPostadresse()
        );
        $institution->setId_postadresse($idPostadresse);

        $idKostenbeitrag=$this->saveKostenbeitragToKostenbeitragTable(
            $institution->getKostenbeitrag()
        );
        $institution->setId_kostenbeitrag($idKostenbeitrag);

        $idInstitution = parent::save($institution);

        return $idInstitution;
    }



    /**
     * @param $idKontakt
     * @return Kontakt
     * @throws \Exception
     */
    protected function loadKontaktFromKontaktTable($idKontakt)
    {
        /** @var Kontakt $kontakt */
        if (!empty($idKontakt))
        {
            $kontakt = $this->kontaktTable->getRecord($idKontakt);

        } else {
            $kontakt = new Kontakt();
        }

        return $kontakt;
    }

    /**
     * @param $idRechnungsadresse
     * @return Adresse
     * @throws \Exception
     */
    protected function loadAdresseFromAdresseTable($idAdresse)
    {
        /** @var Adresse $adresse */
        if (!empty($idAdresse))
        {
            $adresse = $this->adresseTable->getRecord($idAdresse);

        } else {
            $adresse = new Adresse();
        }

        return $adresse;
    }

    /**
     * @param int $idKostenbeitrag Id from kostenbeitrag
     * @return BaseModel|Kostenbeitrag
     * @throws \Exception
     */
    protected function loadKostenbeitragFromKostenbeitragTable($idKostenbeitrag)
    {
        /** @var Kostenbeitrag $kostenbeitrag */
        if (!empty($idKostenbeitrag))
        {
            $kostenbeitrag = $this->kostenbeitragTable->getRecord($idKostenbeitrag);

        } else {
            $kostenbeitrag = new Kostenbeitrag();
        }

        return $kostenbeitrag;
    }

    /**
     * @param Kontakt $kontakt kontakt
     *
     * @return int kontaktid
     * @throws \Exception
     */
    protected function saveKontaktToKontaktTable(Kontakt $kontakt)
    {
        //we always update the table if the kontakt has an id
        //we don't update the table if the kontakt is empty
        if(!$kontakt->isEmpty()) {
            $idKontakt = $this->kontaktTable->save($kontakt);
            return $idKontakt;
        } else {
            return null;
        }
    }

    protected function saveAdresseToAdresseTable(Adresse $adresse)
    {
        //we always update the table if the adresse has an id
        //we don't update the table if the adresse is empty
        if(!$adresse->isEmpty()) {
            $idAdresse = $this->adresseTable->save($adresse);
            return $idAdresse;
        } else {
            return null;
        }
    }

    protected function saveKostenbeitragToKostenbeitragTable(Kostenbeitrag $kostenbeitrag)
    {
        if(!$kostenbeitrag->isEmpty()) {
            $idKostenbeitrag = $this->kostenbeitragTable->save($kostenbeitrag);
            return $idKostenbeitrag;
        } else {
            return null;
        }
    }

    public function deleteKontakt($idKontakt) {
        $this->kontaktTable->delete($idKontakt);
    }


    public function deleteKostenbeitrag($idKostenbeitrag) {
        $this->kostenbeitragTable->delete($idKostenbeitrag);
    }

    public function deleteAdresse($idAdresse) {
        $this->adresseTable->delete($idAdresse);
    }

}
