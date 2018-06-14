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
class InstitutionTable extends BaseTable
{

    /**
     * @var InstitutionRelationTable
     */
    protected $relationTable;

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
     * @var    String[]    Fulltext search fields
     */
    protected $searchFields = array(
        'bib_code',
        'sys_code',
        'label_de',
        'label_fr'
    );



    /**
     * Find institutions
     *
     * @param    String            $searchString
     * @param    Integer            $limit
     * @param    String            $order
     * @return    BaseModel[]
     */
    public function find($searchString, $limit = 30, $order = 'bib_code')
    {
        return $this->findFulltext($searchString, $order, $limit);
    }



    /**
     * Get all institutions
     *
     * @param   String            $order
     * @param   Integer            $limit
     * @return    ResultSetInterface
     */
    public function getAll($order = 'bib_code', $limit = 30)
    {
        return parent::getAll($order, $limit);
    }



    /**
     * Get all institutions as array
     *
     * @param    String         $order
     * @param    Integer        $limit
     * @return   array
     */
    public function getAllToList($order = 'bib_code', $limit = 30, bool $idAsIndex = false)
    {
        $resultSetInterface = $this->getAll($order, $limit);

        return $this->toList($resultSetInterface,$idAsIndex);
    }

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
        InstitutionRelationTable $relationTable,
        AdresseTable $adresseTable,
        KontaktTable $kontaktTable,
        KostenbeitragTable $kostenbeitragTable
    ) {
        parent::__construct($institutionTableGateway);

        $this->relationTable = $relationTable;
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
     * Get all institutions which are related to the given view
     *
     * @param   Integer        $idView
     * @param   String         $order
     * @return    null|ResultSetInterface
     */
    public function getViewInstitutions($idView)
    {
        $select = new Select($this->getTable());

        $select->columns(array('*'))
            ->join(array(
                'mm' => 'mm_institution_group_view'),
                'institution.id = mm.id_institution'
            )
            ->where(array(
                'mm.id_view' => (int)$idView
            ))
            ->group('institution.id');

//            $sql = new Sql($this->tableGateway->getAdapter(), $this->getTable());
//            var_dump($sql->getSqlStringForSqlObject($select));

        return $this->tableGateway->selectWith($select);
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
        $institution->setId_kontakt($idKontakt_rechnung);

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

        $relations = $institution->getRelations();
        $this->saveRelations($idInstitution, $relations);

        return $idInstitution;
    }

    /**
     * method is only used for the import of excel data because the save method tries
     * to store group relations which is not useful here
     * @param Institution $institution
     * @return int
     * @throws \Exception
     * @uses
     */
    public function saveInstitutionOnly(Institution $institution) {
        $idInstitution = parent::save($institution);
        return $idInstitution;
    }



    /**
     * Save institution relations
     *
     * @param    Integer        $idInstitution
     * @param    InstitutionRelation[]    $relations
     */
    protected function saveRelations($idInstitution, array $relations)
    {
        $institutionRelations = $this->relationTable->getInstitutionRelations($idInstitution);
        if (!$this->relationsChanged($institutionRelations, $relations)) return;

        $this->relationTable->deleteInstitutionRelations($idInstitution);

        foreach ($relations as $relation) {
            if ($relation->hasView()) {
                $relation->setIdInstitution($idInstitution);
                $this->relationTable->add($relation);
            }
        }
    }



    /**
     * @param InstitutionRelation[] $oldRelations
     * @param InstitutionRelation[] $newRelations
     *
     * @return bool
     */
    private function relationsChanged(array $oldRelations, array $newRelations) {
        $filteredNewRelations = array();

        //Filter the views that are not checked
        foreach ( $newRelations as $newRelation ) {
            if ( $newRelation->hasView() ) $filteredNewRelations[$newRelation->getPrimaryKey()] = $newRelation;
        }

        //Check if the numbers of relations match
        if ( count($oldRelations) !== count($filteredNewRelations) ) return true;

        //Check the relations for equality
        foreach ( $oldRelations as $oldRelation ) {
            if ( !array_key_exists($oldRelation->getPrimaryKey(), $filteredNewRelations) ) return true;
            if ( !$oldRelation->equals($filteredNewRelations[$oldRelation->getPrimaryKey()]) ) return true;
        }

        return false;
    }



    /**
     * Get all institutions which are related with a view and group
     *
     * @param    Integer        $idView
     * @param    Integer        $idGroup
     * @param    Boolean        $activeOnly
     * @param    Boolean        $favoriteOnly
     * @return    null|ResultSetInterface
     */
    public function getAllGroupViewInstitutions($idView, $idGroup, $activeOnly = true, $favoriteOnly = false)
    {
        $select = new Select($this->getTable());

        $select->columns(array('*'))
                ->join(array(
                        'mm' => 'mm_institution_group_view'),
                        'institution.id = mm.id_institution',
                        array('is_favorite'),
                        $select::JOIN_LEFT
                    )
                ->where(array(
                    'mm.id_view' => (int)$idView,
                    'mm.id_group' => (int)$idGroup
                ))
                ->order('institution.label_de ASC');

        if ($activeOnly) {
            $select->where(array(
                'institution.is_active' => 1
            ));
        }

        if ($favoriteOnly) {
            $select->where(array(
                'mm.is_favorite' => 1
            ));
        }

//        $sql = new Sql($this->tableGateway->getAdapter(), $this->getTable());
//        var_dump($sql->getSqlStringForSqlObject($select));

        return $this->tableGateway->selectWith($select);
    }

    public function bibCodeExists($bibcode) :bool
    {

        $select = new Select($this->getTable());
        $select->where->equalTo("bib_code",$bibcode);

        return  $this->tableGateway->selectWith($select)->count() > 0;

    }

    public function getInstitutionBasedOnField($value, $field = 'bib_code')
    {

        $select = new Select($this->getTable());
        $select->where->equalTo($field,$value);
        return  $this->tableGateway->selectWith($select)->current();
        //$bla = $result->
        //$resultSet = new HydratingResultSet(new ReflectionHydrator, new Institution());
        //$resultSet->initialize($result);

        //foreach ($resultSet as $institution) {
        //    $bla = "";
        //}

        //return  $this->tableGateway->selectWith($select)->count() > 0;

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
}
