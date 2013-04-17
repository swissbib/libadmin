<?php
namespace Libadmin\Controller;


use Libadmin\Table\ViewTable;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

use Libadmin\Controller\BaseController;
use Libadmin\Table\GroupTable;
use Libadmin\Form\GroupForm;
use Libadmin\Model\Group;


/**
 * [Description]
 *
 */
class GroupController extends BaseController {

	/**
	 * @return	GroupForm
	 */
	protected function getGroupForm() {
		return $this->serviceLocator->get('GroupForm');
	}



	/**
	 * Show edit form and add data
	 *
	 * @return	ViewModel|Response
	 */
	public function addAction() {
		$form			= $this->getGroupForm(); // new GroupForm();
		$group			= new Group();
		$request		= $this->getRequest();
		$flashMessenger	= $this->flashMessenger();

		$form->bind($group);

		if( $request->isPost() ) {
//			$form->setInputFilter($group->getInputFilter());
			$form->setData($request->getPost());

			if( $form->isValid() ) {
				$group->exchangeArray($form->getData());

				try {
					$idGroup	= $this->getTable()->save($group);

					$flashMessenger->addSuccessMessage($this->translate('saved_group'));

					return $this->redirectTo('edit', $idGroup);
				} catch(\Exception $e) {
					$flashMessenger->addErrorMessage($e->getMessage());
				}
			} else {
				$flashMessenger->addErrorMessage($this->translate('form_invalid'));
			}
		}

		$form->setAttribute('action', $this->makeUrl('group', 'add'));

		return $this->getAjaxView(array(
			'form'	=> $form,
			'title'	=> $this->translate('group_add', 'Libadmin'),
		), 'libadmin/group/edit');
	}



	/**
	 * Show edit form and update data
	 *
	 * @return	ViewModel
	 */
	public function editAction() {
		$idGroup	= (int)$this->params()->fromRoute('id', 0);
		$flashMessenger	= $this->flashMessenger();

		if( !$idGroup ) {
			return $this->forwardTo('home');
		}

		try {
			/** @var Group $group  */
			$group = $this->getTable()->getRecord($idGroup);
			$group->setViews($this->getTable()->getViewIDs($idGroup));
		} catch(\Exception $ex ) {
			$flashMessenger->addErrorMessage($this->translate('notfound_record'));
//			$flashMessenger->addErrorMessage($ex->getMessage());

			return $this->forwardTo('home');
		}

		$form = $this->getGroupForm(); // new GroupForm();
		$form->bind($group);

		$request = $this->getRequest();
		if( $request->isPost() ) {
			$form->setData($request->getPost());

			if( $form->isValid() ) {
				$this->getTable()->save($form->getData());
				$flashMessenger->addSuccessMessage($this->translate('saved_group'));
			} else {
				$flashMessenger->addErrorMessage($this->translate('form_invalid'));
			}
		}

		$form->setAttribute('action', $this->makeUrl('group', 'edit', $idGroup));

		return $this->getAjaxView(array(
			'form'		=> $form,
			'title'		=> $this->translate('group_edit', 'Libadmin'),
		));
	}

}