<?php
namespace Libadmin\Controller;

//use RecursiveIteratorIterator;

use Libadmin\Form\AdminInstitutionForm;
use Libadmin\Model\AdminInstitution;
use Libadmin\Model\InstitutionRelation;
use Libadmin\Table\AdminInstitutionTable;
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

    public function __construct(AdminInstitutionForm $adminInstitutionForm,
                                AdminInstitutionTable $adminInstitutionTable)
    {


        $this->adminInstitutionForm = $adminInstitutionForm;
        $this->adminInstitutionTable = $adminInstitutionTable;
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
        //damit hääten wir hier auch keine Abhängigkeit meir nach InstitutionRelation Table


        //todo: wie bei Institutions - lege diese Methode in die table
        /** @var AdminInstitution $admininstitution */
        $admininstitution = $this->adminInstitutionTable->getRecord($idInstitution);

        return $admininstitution;
    }




}
