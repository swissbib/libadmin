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
     * @var InstitutionAdminInstitutionRelationTable
     */
    private $institutionAdminInstitutionRelationTable;
    /**
     * @var InstitutionTable
     */
    private $institutionTable;

    public function __construct(
        AdminInstitutionForm $adminInstitutionForm,
        AdminInstitutionTable $adminInstitutionTable,
        InstitutionAdminInstitutionRelationTable $institutionAdminInstitutionRelationTable,
        InstitutionTable $institutionTable
    ) {
        $this->adminInstitutionForm = $adminInstitutionForm;
        $this->adminInstitutionTable = $adminInstitutionTable;
        $this->institutionAdminInstitutionRelationTable = $institutionAdminInstitutionRelationTable;
        $this->institutionTable = $institutionTable;
    }

    /**
     * Initial view
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'listItems' => $this->adminInstitutionTable->getAll('name', 100)
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

        /** @var FlashMessenger $flashMessenger */
        $flashMessenger = $this->flashMessenger();

        $flashMessenger->clearMessages('success');
        $flashMessenger->clearCurrentMessages('success');

        $flashMessenger->clearMessages('error');
        $flashMessenger->clearCurrentMessages('error');

        try {
            /** @var AdminInstitution $admininstitution */
            $admininstitution = $this->getAdminInstitutionForEdit($idInstitution);
        } catch (\Exception $ex) {
            $this->flashMessenger()->addErrorMessage('notfound_record');
            return $this->forwardTo('home');
        }

        $form = $this->adminInstitutionForm;
        $form->bind($admininstitution);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->adminInstitutionTable->save($form->getData());
                $this->flashMessenger()->addSuccessMessage('saved_admin_institution');
                $form->bind($this->getAdminInstitutionForEdit($idInstitution)); // Reload data
            } else {
                $this->flashMessenger()->addErrorMessage('form_invalid');
            }
        }

        $form->setAttribute('action', $this->makeUrl('admininstitution', 'edit', $idInstitution));

        return $this->getAjaxView([
            'customform' => $form,
            'title' => 'admininstitution_edit',
            'isNew' => false,
            'institutions' => $this->institutionTable->getInstitutionsForAnAdminInstitution($idInstitution),
        ]);
    }

    /**
     * Add admin institution
     *
     * @return Response|ViewModel
     */
    public function addAction()
    {
        $form = $this->adminInstitutionForm;
        $adminInstitution = $this->getAdminInstitutionForAdd();

        /** @var Request $request */
        $request = $this->getRequest();

        $form->bind($adminInstitution);

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $adminInstitution->exchangeArray($form->getData());
                $idAdminInstitution = $this->adminInstitutionTable->save($adminInstitution);

                $this->flashMessenger()->addSuccessMessage('saved_admin_institution');

                return $this->redirectTo('edit', $idAdminInstitution);
            } else {
                $this->flashMessenger()->addErrorMessage('form_invalid');
            }
        }

        $form->setAttribute('action', $this->makeUrl('admininstitution', 'add'));

        return $this->getAjaxView(array(
            'customform' => $form,
            'title' => 'admininstitution_add',
        ), 'libadmin/admin-institution/edit');
    }


    /**
     * Get institution prepared to be bound to the form
     *
     * @param   Integer $idInstitution
     * @return    AdminInstitution
     */
    protected function getAdminInstitutionForEdit($idInstitution)
    {
        /** @var AdminInstitution $admininstitution */
        $admininstitution = $this->adminInstitutionTable->getRecord($idInstitution);

        return $admininstitution;
    }

    /**
     * Get admin institution prepared to be bound to the form
     *
     * @return    AdminInstitution
     */
    protected function getAdminInstitutionForAdd()
    {
        $adminInstitution = new AdminInstitution();

        $kontakt = new Kontakt();
        $adminInstitution->setKontakt($kontakt);

        $kontakt_rechnung = new Kontakt();
        $adminInstitution->setKontakt_rechnung($kontakt_rechnung);

        $rechnungsadresse = new Adresse();
        $adminInstitution->setRechnungsadresse($rechnungsadresse);

        $postadresse = new Adresse();
        $adminInstitution->setPostadresse($postadresse);

        $kostenbeitrag = new Kostenbeitrag();
        $adminInstitution->setKostenbeitrag($kostenbeitrag);

        return $adminInstitution;
    }

    /**
     * Search matching records
     *
     * @param	Integer		$limit        Search result limit
     * @return	ViewModel
     **/
    public function searchAction($limit = 50)
    {
        $query = $this->params()->fromQuery('query', '');
        $data = array(
            'route' => strtolower($this->getTypeName()),
            'listItems' => $this->adminInstitutionTable->find($query, $limit)
        );

        return $this->getAjaxView($data, 'libadmin/global/search');
    }


    public function deleteAction()
    {

        $idRecord = (int)$this->params()->fromRoute('id', 0);

        if (!$idRecord) {
            $this->flashMessenger()->addErrorMessage('No record defined for deletion. Something went wrong');
            return $this->redirectTo('home');
        }


        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $isDeleteRequest = $request->getPost('del') !== null;

            if ($isDeleteRequest) {
                $idRecord = (int)$request->getPost('id');

                /**
                 * @var AdminInstitution $admininstitution
                 */
                $this->beforeDelete($idRecord);
                $this->adminInstitutionTable->delete($idRecord);
                $this->flashMessenger()->addSuccessMessage('Record deleted');

                return $this->redirectTo('home');
            }
        }
        return $this->getAjaxView([
            'id' => $idRecord,
            'route' => 'admininstitution',
            'record' => $this->adminInstitutionTable->getRecord($idRecord)
        ], 'libadmin/global/delete');

    }


    protected function beforeDelete($idAdminInstitution)
    {
        $this->institutionAdminInstitutionRelationTable->deleteWithIdAdmin($idAdminInstitution);

    }


    public function homeAction()
    {


        return $this->getAjaxView(
            [
                'listItems' => $this->adminInstitutionTable->getAll()
            ]);
    }




}
