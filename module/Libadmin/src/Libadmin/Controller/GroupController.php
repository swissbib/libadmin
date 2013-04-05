<?php
namespace Libadmin\Controller;


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
	 * Initial group view
	 *
	 * @return array
	 */
	public function indexAction() {
		return array(
			'searchResults' => $this->getTable()->getAll(30)
		);
	}



	/**
	 * Home view
	 *
	 * @return	ViewModel
	 */
	public function homeAction() {
		return $this->getAjaxView();
	}



	/**
	 * Show edit form and add data
	 *
	 * @return	ViewModel|Response
	 */
	public function addAction() {
		$form			= new GroupForm();
		$request		= $this->getRequest();
		$flashMessenger	= $this->flashMessenger();

		if( $request->isPost() ) {
			$group = new Group();
			$form->setInputFilter($group->getInputFilter());
			$form->setData($request->getPost());

			if( $form->isValid() ) {
				$group->exchangeArray($form->getData());
				$idGroup	= $this->getTable()->save($group);

				$flashMessenger->addSuccessMessage('New group added');

				return $this->redirectTo('edit', $idGroup);
			} else {
				$flashMessenger->addErrorMessage('Form not valid');
			}
		}

		$form->setAttribute('action', $this->makeUrl('group', 'add'));

		return $this->getAjaxView(array(
			'form'	=> $form,
			'title'	=> 'Add Group'
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
		} catch(\Exception $ex ) {
			$flashMessenger->addErrorMessage('Group not found');

			return $this->forwardTo('home');
		}

		$form = new GroupForm();
		$form->bind($group);

		$request = $this->getRequest();
		if( $request->isPost() ) {
			$form->setInputFilter($group->getInputFilter());
			$form->setData($request->getPost());

			if( $form->isValid() ) {
				$this->getTable()->save($form->getData());
				$flashMessenger->addSuccessMessage('Group saved');
			} else {
				$flashMessenger->addErrorMessage('Form not valid');
			}
		}

		$form->setAttribute('action', $this->makeUrl('group', 'edit', $idGroup));

		return $this->getAjaxView(array(
			'form'		=> $form,
			'title'		=> 'Edit Group'
		));
	}



	/**
	 * @return    GroupTable
	 */
	protected function getTable() {
		if( !$this->table ) {
			$this->table = $this->getServiceLocator()->get('Libadmin\Table\GroupTable');
		}
		return $this->table;
	}

}

?>