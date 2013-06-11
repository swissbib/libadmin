<?php
namespace Libadmin\Controller;

use Libadmin\Model\InstitutionRelationList;
use Libadmin\Table\GroupRelationTable;
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
	 * Search groups
	 * Extend limit to always see all items
	 *
	 * @return ViewModel
	 */
	public function searchAction()
	{
		return parent::searchAction(300);
	}



	/**
	 * Show edit form and add data
	 *
	 * @return    ViewModel|Response
	 */
	public function addAction()
	{
		$form	= $this->getGroupForm();
		$group	= new Group();
		/** @var Request $request */
		$request= $this->getRequest();
		$flashMessenger = $this->flashMessenger();

		$form->bind($group);

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
//				$group->exchangeArray($form->getData());

				try {
					$storageData= $form->getData(FormInterface::VALUES_AS_ARRAY);
					$idGroup	 = $this->getTable()->save($storageData);

					$flashMessenger->addSuccessMessage($this->translate('saved_group'));

					return $this->redirectTo('edit', $idGroup);
				} catch (\Exception $e) {
					$flashMessenger->addErrorMessage($e->getMessage());
				}
			} else {
				$flashMessenger->addErrorMessage($this->translate('form_invalid'));
			}
		}

		$form->setAttribute('action', $this->makeUrl('group', 'add'));

		return $this->getAjaxView(array(
			'form' 		=> $form,
			'lockLists'	=> array(),
			'title'		=> $this->translate('group_add', 'Libadmin'),
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
		$flashMessenger = $this->flashMessenger();

		if (!$idGroup) {
			return $this->forwardTo('home');
		}

		try {
			/** @var Group $group */
			$group	= $this->getGroupForEdit($idGroup);
		} catch (\Exception $ex) {
			$flashMessenger->addErrorMessage($this->translate('notfound_record'));
			$flashMessenger->addErrorMessage($ex->getMessage());

			return $this->forwardTo('home');
		}

		$form = $this->getGroupForm();
		$form->bind($group);

		/** @var Request $request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$storageData	= $form->getData(FormInterface::VALUES_AS_ARRAY);
				$this->getTable()->save($storageData, $idGroup);
				$flashMessenger->addSuccessMessage($this->translate('saved_group'));
				$group	= $this->getGroupForEdit($idGroup);
				$form->bind($group);
			} else {
				$flashMessenger->addErrorMessage($this->translate('form_invalid'));
			}
		}

		$form->setAttribute('action', $this->makeUrl('group', 'edit', $idGroup));

		return $this->getAjaxView(array(
			'form'		=> $form,
			'lockLists'	=> $this->getInstitutionLockLists(),
			'title' 	=> $this->translate('group_edit', 'Libadmin'),
		));
	}



	/**
	 * Get list of institutions grouped by their relation to the views
	 *
	 * @return	Array[]
	 */
	protected function getInstitutionLockLists()
	{
		$lockLists	= array();

		foreach ($this->getAllViews() as $view) {
			$lockLists[$view->getId()] = $this->getTable()->getViewInstitutionIDs($view->getId());
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
		$views			= $this->getAllViews();
		$institutions	= $this->getInstitutions('bib_code', 0);

		return new GroupForm($views, $institutions);
	}



	/**
	 *
	 * @param	Integer		$idGroup
	 * @return	Group
	 */
	protected function getGroupForEdit($idGroup)
	{
		$group		= $this->getTable()->getRecord($idGroup);
		$views		= $this->getAllViews();
		$relations	= array();
		/** @var InstitutionRelationTable $relationTable */
		$relationTable	= $this->getTable('InstitutionRelation');
		$groupViewIds	= $this->getTable('Group')->getViewIDs($idGroup);

		foreach ($views as $view) {
			$viewRelations	= $relationTable->getGroupViewRelations($idGroup, $view->getId());
			$relations[]	= new InstitutionRelationList($view->getId(), $viewRelations);
		}

		$group->setRelations($relations);
		$group->setViews($groupViewIds);

		return $group;
	}



	/**
	 * Before group delete, remove all relations
	 *
	 * @param	Integer		$idGroup
	 */
	protected function beforeDelete($idGroup)
	{
		$this->getGroupRelationTable()->deleteGroupRelations($idGroup);
		$this->getInstitutionRelationTable()->deleteGroupRelations($idGroup);
	}
}
