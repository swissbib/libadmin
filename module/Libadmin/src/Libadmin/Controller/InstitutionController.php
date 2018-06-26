<?php
namespace Libadmin\Controller;

//use RecursiveIteratorIterator;

use Libadmin\Model\Adresse;
use Libadmin\Model\InstitutionRelation;
use Libadmin\Model\Kostenbeitrag;
use Libadmin\Table\InstitutionRelationTable;
use Libadmin\Table\InstitutionTable;
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
use Libadmin\Model\Kontakt;

/**
 * [Description]
 *
 */
class InstitutionController extends BaseController
{

    /** @var InstitutionForm */
    private $institutionForm;

    /** @var  InstitutionTable */
    private $institutionTable;

    /** @var  InstitutionRelationTable */
    private $institutionRelationTable;

    /** @var array  */
    private $allViews = [];


    /**
     * InstitutionController constructor.
     *
     * @param InstitutionForm $institutionForm
     * @param InstitutionTable $institutionTable
     * @param InstitutionRelationTable $institutionRelationTable

     * @param array $allViews
     */
    public function __construct(
        InstitutionForm $institutionForm,
        InstitutionTable $institutionTable,
        InstitutionRelationTable $institutionRelationTable,
        array $allViews
    ) {
        $this->institutionForm = $institutionForm;
        $this->institutionTable = $institutionTable;
        $this->institutionRelationTable = $institutionRelationTable;
        $this->allViews = $allViews;
    }



    /**
     * Add institution
     *
     * @return Response|ViewModel
     */
    public function addAction()
    {
        $form = $this->institutionForm;
        $institution = $this->getInstitutionForAdd();

        /** @var Request $request */
        $request = $this->getRequest();

        $form->bind($institution);

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $institution->exchangeArray($form->getData());
                $idInstitution = $this->institutionTable->save($institution);

                $this->flashMessenger()->addSuccessMessage('saved_institution');

                return $this->redirectTo('edit', $idInstitution);
            } else {
                $this->flashMessenger()->addErrorMessage('form_invalid');
            }
        }

        $form->setAttribute('action', $this->makeUrl('institution', 'add'));

        return $this->getAjaxView(array(
            'customform' => $form,
            'title' => 'institution_add',
        ), 'libadmin/institution/edit');
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
            /** @var Institution $institution */
            $institution = $this->getInstitutionForEdit($idInstitution);

        } catch (\Exception $ex) {
            $flashMessenger->addErrorMessage('notfound_record');

            return $this->forwardTo('home');
        }

        $form = $this->institutionForm;
        $form->bind($institution);

        /** @var Request $request */
        $request = $this->getRequest();


        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                //try {
                    /** @var Institution $data */
                    $data = $form->getData();

                    $this->institutionTable->save($data);
                    $flashMessenger->addSuccessMessage('saved_institution');
                    $form->bind($this->getInstitutionForEdit($idInstitution)); // Reload data
                //} catch (\Exception $ex) {
                //    $this->flashMessenger()->addErrorMessage($ex->getMessage());
                //}
            } else {
                $flashMessenger->addErrorMessage('form_invalid');
            }
        }

        $form->setAttribute('action', $this->makeUrl('institution', 'edit', $idInstitution));

        return $this->getAjaxView(array(
            'customform' => $form,
            'title' => 'institution_edit'
        ));
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
                $this->beforeDelete($idRecord);
                $this->institutionTable->delete($idRecord);
                $this->afterDelete($idRecord);
                // @todo message is shown to late, solve this problem and re-enable message
                //	$this->flashMessenger()->addSuccessMessage('Record deleted');
            }


            return $this->redirect()->toRoute('institution', ['action' => 'index']);
            //return $this->forward()->dispatch(InstitutionController::class,$params);

        }

        return $this->getAjaxView(array(
            'id' => $idRecord,
            'route' => 'institution',
            'record' => $this->institutionTable->getRecord($idRecord)
        ), 'libadmin/global/delete');
    }

    public function homeAction()
    {
        return $this->getAjaxView(
            [
                'listItems' => $this->institutionTable->getAll()
            ]);
    }


    /**
     * Get institution prepared to be bound to the form
     *
     * @param   Integer $idInstitution
     * @return    Institution
     */
    protected function  getInstitutionForEdit($idInstitution)
    {
        $institution = $this->institutionTable->getRecord($idInstitution);
        $views = $this->allViews;
        /** @var InstitutionRelationTable $relationTable */
        $relationTable = $this->institutionRelationTable;
        /** @var InstitutionRelation[] $existingRelations */
        $existingRelations = $relationTable->getInstitutionRelations($idInstitution);
        $relations = array();

        foreach ($views as $view) {
            foreach ($existingRelations as $index => $existingRelation) {
                if ($view->getId() == $existingRelation->getIdView()) {
                    $relations[] = $existingRelation;
                    unset($existingRelations[$index]);
                    continue 2;
                }
            }
            $relations[] = new InstitutionRelation();
        }

        $institution->setRelations($relations);






        return $institution;
    }


    /**
     * Get institution prepared to be bound to the form
     *
     * @return    Institution
     */
    protected function getInstitutionForAdd()
    {
        $views = $this->allViews;
        $institution = new Institution();

        $kontakt = new Kontakt();
        $institution->setKontakt($kontakt);

        $kontakt_rechnung = new Kontakt();
        $institution->setKontakt_rechnung($kontakt_rechnung);

        $rechnungsadresse = new Adresse();
        $institution->setRechnungsadresse($rechnungsadresse);

        $postadresse = new Adresse();
        $institution->setPostadresse($postadresse);

        $kostenbeitrag = new Kostenbeitrag();
        $institution->setKostenbeitrag($kostenbeitrag);

        $relations = array();

        foreach ($views as $view) {
            $relations[] = new InstitutionRelation();
        }

        $institution->setRelations($relations);

        return $institution;
    }

    /**
     * Before institution delete, remove all relations
     *
     * @param    Integer $idView
     */
    protected function beforeDelete($idView)
    {
        $this->institutionRelationTable->deleteInstitutionRelations($idView);
    }

    /**
     * Initial view
     *
     * @return array
     */
    public function indexAction()
    {

        return [
            'listItems' => $this->institutionTable->getAll()
        ];
    }

    /**
     * Search matching records
     *
     * @param	Integer		$limit        Search result limit
     * @return	ViewModel
     **/
    public function searchAction($limit = 15)
    {
        $query = $this->params()->fromQuery('query', '');
        $data = array(
            'route' => strtolower($this->getTypeName()),
            'listItems' => $this->institutionTable->find($query, $limit)
        );

        return $this->getAjaxView($data, 'libadmin/global/search');
    }



}
