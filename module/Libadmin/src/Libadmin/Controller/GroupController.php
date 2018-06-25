<?php
namespace Libadmin\Controller;

use Libadmin\Model\InstitutionRelationList;
use Libadmin\Table\GroupRelationTable;
use Libadmin\Table\GroupTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\ViewTable;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;
use Zend\Http\Request;

use Libadmin\Controller\BaseController;
use Libadmin\Form\GroupForm;
use Libadmin\Model\Group;
use Libadmin\Table\InstitutionRelationTable;
use Libadmin\Model\InstitutionRelation;

/**
 * [Description]
 *
 */
class GroupController extends BaseController
{


    /**
     * @var GroupForm
     */
    private $groupForm;
    /**
     * @var GroupTable
     */
    private $groupTable;
    /**
     * @var ViewTable
     */
    private $viewTable;
    /**
     * @var InstitutionTable
     */
    private $institutionTable;
    /**
     * @var InstitutionRelationTable
     */
    private $institutionRelationTable;
    /**
     * @var GroupRelationTable
     */
    private $groupRelationTable;

    /**
     * GroupController constructor.
     * @param GroupForm $groupForm
     * @param GroupTable $groupTable
     * @param ViewTable $viewTable
     * @param InstitutionTable $institutionTable
     * @param InstitutionRelationTable $institutionRelationTable
     * @param GroupRelationTable $groupRelationTable
     */
    public function __construct(
        GroupForm $groupForm,
        GroupTable $groupTable,
        ViewTable $viewTable,
        InstitutionTable $institutionTable,
        InstitutionRelationTable $institutionRelationTable,
        GroupRelationTable $groupRelationTable
    )
    {
        $this->groupForm = $groupForm;
        $this->groupTable = $groupTable;
        $this->viewTable = $viewTable;
        $this->institutionTable = $institutionTable;
        $this->institutionRelationTable = $institutionRelationTable;
        $this->groupRelationTable = $groupRelationTable;
    }


    /**
     * Search groups
     * Extend limit to always see all items
     *
     * @return ViewModel
     */
    public function searchAction($limit = 15)
    {
        //return parent::searchAction(300);

        $query = $this->params()->fromQuery('query', '');
        $data = array(
            'route' => 'group',
            'listItems' => $this->groupTable->find($query, $limit)
        );

        return $this->getAjaxView($data, 'libadmin/global/search');

    }


    /**
     * Initial view
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'listItems' => $this->groupTable->getAll()
        ];
    }



    /**
     * Show edit form and add data
     *
     * @return    ViewModel|Response
     */
    public function addAction()
    {
        $form = $this->groupForm;
        $group = new Group();
        /** @var Request $request */
        $request = $this->getRequest();

        $form->bind($group);

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
//				$group->exchangeArray($form->getData());

                try {
                    $storageData = $form->getData(FormInterface::VALUES_AS_ARRAY);
                    $group->exchangeArray($storageData['group']);
                    $idGroup = $this->groupTable->save($group);

                    $this->flashMessenger()->addSuccessMessage('saved_group');

                    return $this->redirectTo('edit', $idGroup);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
            } else {
                $this->flashMessenger->addErrorMessage('form_invalid');
            }
        }

        $form->setAttribute('action', $this->makeUrl('group', 'add'));

        return $this->getAjaxView(array(
            'customform' => $form,
            'lockLists' => array(),
            'title' => 'group_add',
        ), 'libadmin/group/edit');
    }


    /**
     * Show edit form and update data
     *
     * @return    ViewModel
     */
    public function editAction()
    {
        $idGroup = (int)$this->params()->fromRoute('id', 0);

        if (!$idGroup) {
            return $this->forwardTo('home');
        }

        /** @var FlashMessenger $flashMessenger */
        $flashMessenger = $this->flashMessenger();

        $flashMessenger->clearMessages('success');
        $flashMessenger->clearCurrentMessages('success');

        $flashMessenger->clearMessages('error');
        $flashMessenger->clearCurrentMessages('error');

        try {
            /** @var Group $group */
            $group = $this->getGroupForEdit($idGroup);
        } catch (\Exception $ex) {
            $this->flashMessenger()->addErrorMessage('notfound_record');
            $this->flashMessenger()->addErrorMessage($ex->getMessage());

            return $this->forwardTo('home');
        }

        $form = $this->groupForm;
        $form->bind($group);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $storageData = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $group = new Group();
                $group->exchangeArray($storageData['group']);
                $group->setId($idGroup);

                $this->groupTable->save($group);
                $this->flashMessenger()->addSuccessMessage('saved_group');
                $group = $this->getGroupForEdit($idGroup);
                $form->bind($group);
            } else {
                $this->flashMessenger()->addErrorMessage('form_invalid');
            }
        }

        $form->setAttribute('action', $this->makeUrl('group', 'edit', $idGroup));

        return $this->getAjaxView([
            'customform' => $form,
            'lockLists' => $this->getInstitutionLockLists(),
            'title' => 'group_edit',
        ]);
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
                $this->groupTable->delete($idRecord);
                $this->afterDelete($idRecord);
                // @todo message is shown to late, solve this problem and re-enable message
                //	$this->flashMessenger()->addSuccessMessage('Record deleted');
            }


            return $this->redirect()->toRoute('group', ['action' => 'index']);
            //return $this->forward()->dispatch(InstitutionController::class,$params);

        }

        return $this->getAjaxView(array(
            'id' => $idRecord,
            'route' => 'group',
            'record' => $this->groupTable->getRecord($idRecord)
        ), 'libadmin/global/delete');
    }


    public function homeAction()
    {
        return $this->getAjaxView(
            [
                'listItems' => $this->groupTable->getAll()
            ]);
    }



    /**
     * Get list of institutions grouped by their relation to the views
     *
     * @return    array[]
     */
    protected function getInstitutionLockLists()
    {
        $lockLists = [];

        foreach ($this->viewTable->getAll('id', 0) as $view) {
            $lockLists[$view->getId()] = $this->viewTable->getViewInstitutionIDs($view->getId());
        }

        return $lockLists;
    }


    /**
     * Get group form
     *
     * @return    GroupForm
     */
    protected function getGroupForm()
    {
        $views = $this->getAllViews();
        $institutions = $this->getInstitutions('bib_code', 0);

        return new GroupForm($views, $institutions);
    }


    /**
     *
     * @param    Integer $idGroup
     * @return    Group
     */
    protected function getGroupForEdit($idGroup)
    {
        $group = $this->groupTable->getRecord($idGroup);
        //$views = $this->getAllViews();
        $views = $this->viewTable->getAllToList("id", 0);
        $relations = [];
        /** @var InstitutionRelationTable $relationTable */
        $relationTable = $this->institutionRelationTable;
        $groupViewIds = $this->groupTable->getViewIDs($idGroup);

        foreach ($views as $view) {
            $viewRelations = $relationTable->getGroupViewRelations($idGroup, $view->getId());
            $relations[] = new InstitutionRelationList($view->getId(), $viewRelations);
        }

        $group->setRelations($relations);
        $group->setViews($groupViewIds);

        return $group;
    }


    /**
     * Before group delete, remove all relations
     *
     * @param    Integer $idGroup
     */
    protected function beforeDelete($idGroup)
    {
        $this->groupRelationTable->deleteGroupRelations($idGroup);
        $this->institutionRelationTable->deleteGroupRelations($idGroup);
    }

}