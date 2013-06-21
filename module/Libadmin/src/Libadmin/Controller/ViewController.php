<?php
namespace Libadmin\Controller;

use Zend\Http\Request;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

use Libadmin\Helper\RelationOverview;
use Libadmin\Form\ViewForm;
use Libadmin\Model\View;
use Libadmin\Table\GroupRelationTable;
use Libadmin\Table\GroupTable;

/**
 * [Description]
 *
 */
class ViewController extends BaseController
{


	/**
	 * @return    ViewForm
	 */
	protected function getViewForm()
	{
		return $this->serviceLocator->get('ViewForm');
	}



	/**
	 * Add view
	 *
	 * @return    Response|ViewModel
	 */
	public function addAction()
	{
		$form = $this->getViewForm();
		/** @var Request $request */
		$request = $this->getRequest();
		$flashMessenger = $this->flashMessenger();

		if ($request->isPost()) {
			$view = new View();
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$view->exchangeArray($form->getData());
				$idView = $this->getTable()->save($view);

				$flashMessenger->addSuccessMessage($this->translate('saved_view'));

				return $this->redirectTo('edit', $idView);
			} else {
				$flashMessenger->addErrorMessage($this->translate('form_invalid'));
			}
		}

		$form->setAttribute('action', $this->makeUrl('view', 'add'));

		return $this->getAjaxView(array(
			'form'	=> $form,
			'title'	=> $this->translate('view_add', 'Libadmin'),
		), 'libadmin/view/edit');
	}



	/**
	 * Edit view
	 *
	 * @return    ViewModel
	 */
	public function editAction()
	{
		$idView = (int)$this->params()->fromRoute('id', 0);
		$flashMessenger = $this->flashMessenger();

		if (!$idView) {
			return $this->forwardTo('home');
		}

		try {
			/** @var View $view */
			$view = $this->getTable()->getRecord($idView);
			$view->setGroups($this->getTable()->getGroupIDs($idView));
		} catch (\Exception $ex) {
			$flashMessenger->addErrorMessage($this->translate('notfound_record'));

			return $this->forwardTo('home');
		}

		$form = $this->getViewForm();
		$form->bind($view);

		/** @var Request $request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			$postData = $request->getPost();
			$form->setData($postData);

			if ($form->isValid()) {
				$groupIdsSorted			= $postData->get('groupsortableids');
				$institutionIdsSorted	= $postData->get('institutionsortableids');
				$view					= $form->getData();
				$view->setGroups($postData->get('groups') ?: array()); // Workaround, if all groups are deselected

				$this->getTable()->save($view, $groupIdsSorted, $institutionIdsSorted);

				$form->bind($view);

				$flashMessenger->addSuccessMessage($this->translate('saved_view'));
			} else {
				$flashMessenger->addErrorMessage($this->translate('form_invalid'));
			}
		}

		$form->setAttribute('action', $this->makeUrl('view', 'edit', $idView));

		/** @var GroupTable $groupTable */
		$groupTable	= $this->getTable('Group');

		/** @var GroupTable $groupTable */
		$institutionTable	= $this->getTable('Institution');

		/** @var RelationOverview $relationHelper */
		$relationHelper = $this->serviceLocator->get('RelationOverviewHelper');

		return $this->getAjaxView(array(
			'groups'		=> $groupTable->getViewGroups($idView),
//			'institutions'	=> $this->getInstitutions(),
			'institutions'	=> $institutionTable->getViewInstitutions($idView),

			'form'			=> $form,
			'title'			=> $this->translate('view_edit', 'Libadmin'),
			'relations' 	=> $relationHelper->getData($view)
		));
	}



	/**
	 * Before view delete, remove all relations
	 *
	 * @param    Integer        $idView
	 */
	protected function beforeDelete($idView)
	{
		$this->getGroupRelationTable()->deleteViewRelations($idView);
		$this->getInstitutionRelationTable()->deleteViewRelations($idView);
	}
}
