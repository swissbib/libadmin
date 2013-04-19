<?php
namespace Libadmin\Controller;

use Libadmin\Model\InstitutionRelationList;
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
				$group->exchangeArray($form->getData());

				try {
					$idGroup = $this->getTable()->save($group);

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
			'form' => $form,
			'title' => $this->translate('group_add', 'Libadmin'),
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
				$this->getTable()->save($form->getData());
				$flashMessenger->addSuccessMessage($this->translate('saved_group'));
			} else {
				$flashMessenger->addErrorMessage($this->translate('form_invalid'));
			}
		}

		$form->setAttribute('action', $this->makeUrl('group', 'edit', $idGroup));

		return $this->getAjaxView(array(
			'form' => $form,
			'title' => $this->translate('group_edit', 'Libadmin'),
		));
	}



	/**
	 * Get group form
	 *
	 * @return    GroupForm
	 */
	protected function getGroupForm()
	{
		$views			= $this->getViews();
		$institutions	= $this->getInstitutions();

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
		$views		= $this->getViews();
		$relations	= array();
		/** @var InstitutionRelationTable $relationTable */
		$relationTable		= $this->getTable('InstitutionRelation');

		foreach ($views as $view) {
			$relations[] = new InstitutionRelationList($relationTable->getGroupViewRelations($idGroup, $view->getId()));
		}

		$group->setRelations($relations);

		return $group;
	}
}
