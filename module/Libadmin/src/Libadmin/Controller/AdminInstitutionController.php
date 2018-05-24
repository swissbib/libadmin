<?php
namespace Libadmin\Controller;

//use RecursiveIteratorIterator;

use Libadmin\Form\AdminInstitutionForm;
use Libadmin\Model\AdminInstitution;
use Libadmin\Model\Adresse;
use Libadmin\Model\InstitutionRelation;
use Libadmin\Model\Kontakt;
use Libadmin\Model\Kostenbeitrag;
use Libadmin\Table\AdminInstitutionTable;
use Libadmin\Table\AdresseTable;
use Libadmin\Table\InstitutionAdminInstitutionRelationTable;
use Libadmin\Table\InstitutionRelationTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\KontaktTable;
use Libadmin\Table\KostenbeitragTable;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Http\Request;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;
use Zend\Db\ResultSet\ResultSet;

use Libadmin\Form\InstitutionForm;
use Libadmin\Model\Institution;
use Libadmin\Controller\BaseController;
use Libadmin\Model\View;
use Libadmin\Model\Group;

/**
 * [Description]
 *
 */
class AdminInstitutionController extends BaseController
{

    /**
     * @var AdminInstitutionForm
     */
    private $adminInstitutionForm;
    /**
     * @var AdminInstitutionTable
     */
    private $adminInstitutionTable;
    /**
     * @var KostenbeitragTable
     */
    private $kostenbeitragTable;
    /**
     * @var AdresseTable
     */
    private $adresseTable;
    /**
     * @var KontaktTable
     */
    private $kontaktTable;
    /**
     * @var InstitutionAdminInstitutionRelationTable
     */
    private $institutionAdminInstitutionRelationTable;

    public function __construct(AdminInstitutionForm $adminInstitutionForm,
                                AdminInstitutionTable $adminInstitutionTable,
                                KostenbeitragTable $kostenbeitragTable,
                                AdresseTable $adresseTable,
                                KontaktTable $kontaktTable,
                                InstitutionAdminInstitutionRelationTable $institutionAdminInstitutionRelationTable){



        $this->adminInstitutionForm = $adminInstitutionForm;
        $this->adminInstitutionTable = $adminInstitutionTable;
        $this->kostenbeitragTable = $kostenbeitragTable;
        $this->adresseTable = $adresseTable;
        $this->kontaktTable = $kontaktTable;
        $this->institutionAdminInstitutionRelationTable = $institutionAdminInstitutionRelationTable;
    }

    /**
     * Initial view
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'listItems' => $this->adminInstitutionTable->getAll()
        ];


    }


    /**
     * Edit institution
     *
     * @return ViewModel
     */
    public function editAction()
    {



        $idInstitution = (int)$this->params()->fromRoute('id', 0);

        if (!$idInstitution) {
            return $this->forwardTo('home');
        }

        try {
            /** @var AdminInstitution $admininstitution */
            $admininstitution = $this->getAdminInstitutionForEdit($idInstitution);
            //todo: lookup documentation if empty is the correct method in PHP to check the value of object properties
            //we have to fetch several objects (Adresse, Kontakt, ....) if there is a foreign key value in the
            // admininstitution object
            if (!empty($admininstitution->getIdAdresse())) {
                $adresse = $this->getAdressObjectForEdit($admininstitution->getIdAdresse());
            }

            //weitere Möglichkeit zur Abfrage: adresse_rechnung_gleich_post
            if (!empty($admininstitution->getIdRechnungsadresse())) {
                $adresse = $this->getAdressObjectForEdit($admininstitution->getIdRechnungsadresse());
            }


            if (!empty($admininstitution->getIdKontakt())) {
                $kontakt = $this->getKontaktObjectForEdit($admininstitution->getIdKontakt());
            }


            if (!empty($admininstitution->getIdKontaktRechnung())) {
                $kontaktRechnung = $this->getKontaktObjectForEdit($admininstitution->getIdKontaktRechnung());
            }

            if (!empty($admininstitution->getIdKostenbeitrag())) {
                $kostenbeitrag = $this->getKostenbeitragObjectForEdit($admininstitution->getIdKostenbeitrag());
            }


        } catch (\Exception $ex) {
            $this->flashMessenger()->addErrorMessage('notfound_record');
            return $this->forwardTo('home');
        }

        //jetzt haben wir die einzelnen Objekte
        //wie können wir sie an die Form binden - todo: Beschäftigung Aufbau Formkomponente
        //was machen wir, wenn einzelne Objekte keinen Wert haben (z.B keinen Kontakt Rechnug etc.
        //todo wie binden wir solche empty values??

        $form = $this->adminInstitutionForm;
        $form->bind($admininstitution);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->adminInstitutionTable->save($form->getData());
                $this->flashMessenger()->addSuccessMessage('saved_institution');
                $form->bind($this->getAdminInstitutionForEdit($idInstitution)); // Reload data
            } else {
                $this->flashMessenger()->addErrorMessage('form_invalid');
            }
        }

        $form->setAttribute('action', $this->makeUrl('admininstitution', 'edit', $idInstitution));

        //todo: GH addition of isNew just to avoid Exception in template rendering
        //we have to look up how this mechanism (differentiation between update and new) is implemented elsewhere
        return $this->getAjaxView([
            'customform' => $form,
            'title' => 'admininstitution_edit',
            'isNew' => false
        ]);
    }


    /**
     * Get institution prepared to be bound to the form
     *
     * @param   Integer $idInstitution
     * @return    AdminInstitution
     */
    protected function getAdminInstitutionForEdit($idInstitution)
    {
        //todo
        //diese methoe sollte hier gar nicht sein sondern in die InstitutionTable
        //damit hätten wir hier auch keine Abhängigkeit meir nach InstitutionRelation Table


        //todo: wie bei Institutions - lege diese Methode in die table
        /** @var AdminInstitution $admininstitution */
        $admininstitution = $this->adminInstitutionTable->getRecord($idInstitution);

        return $admininstitution;
    }


    /**
     * @param $idAdress
     * @return Adresse
     * @throws \Exception
     */
    protected function getAdressObjectForEdit($idAdress)
    {
        //todo
        //Abhängigkeit wie oben abklären


        //todo: wie bei Institutions - lege diese Methode in die table
        /** @var Adresse $adress */
        $adress = $this->adresseTable->getRecord($idAdress);

        return $adress;
    }


    /**
     * @param $idKontakt
     * @return Kontakt
     * @throws \Exception
     */
    protected function getKontaktObjectForEdit($idKontakt)
    {
        //todo
        //Abhängigkeit wie oben abklären


        //todo: wie bei Institutions - lege diese Methode in die table
        /** @var Kontakt $kontakt */
        $kontakt = $this->kontaktTable->getRecord($idKontakt);

        return $kontakt;
    }

    protected function getKostenbeitragObjectForEdit($idKostenbeitrag)
    {
        //todo
        //Abhängigkeit wie oben abklären


        //todo: wie bei Institutions - lege diese Methode in die table
        /** @var Kostenbeitrag kostenbeitrag */
        $kostenbeitrag = $this->kostenbeitragTable->getRecord($idKostenbeitrag);

        return $kostenbeitrag;
    }


}
