<?php
namespace Libadmin\Controller;

use Libadmin\Model\InstitutionRelation;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;
use Zend\Db\ResultSet\ResultSet;

use Libadmin\Form\InstitutionForm;
use Libadmin\Model\Institution;
use Libadmin\Controller\BaseController;
use Libadmin\Model\View;


/**
 * [Description]
 *
 */
class InstitutionController extends BaseController {

	/**
	 * Add institution
	 *
	 * @return Response|ViewModel
	 */
	public function addAction() {
		$form			= $this->getInstitutionForm();
		$flashMessenger	= $this->flashMessenger();
        $institution    = new Institution();

		$form->bind($institution);

		if( $this->request->isPost() ) {
//			$form->setInputFilter($institution->getInputFilter());
			$form->setData($this->request->getPost());

			if( $form->isValid()) {
				$institution->exchangeArray($form->getData());
				$idInstitution	= $this->getTable()->save($institution);

				$flashMessenger->addSuccessMessage('New institution added');

				return $this->redirectTo('edit', $idInstitution);
			} else {
				$flashMessenger->addErrorMessage('Form not valid');
//				$messages = $form->getMessages();
//				foreach($form->getMessages() as $message) {
//					$flashMessenger->addErrorMessage($message);
//				}
			}
		}

		$form->setAttribute('action', $this->makeUrl('institution', 'add'));

		return $this->getAjaxView(array(
			'form'	=> $form,
			'title'	=> 'Add Institution'
		), 'libadmin/institution/edit');
	}



	/**
	 * Edit institution
	 *
	 * @return ViewModel
	 */
	public function editAction() {
		$idInstitution	= (int)$this->params()->fromRoute('id', 0);
		$flashMessenger	= $this->flashMessenger();

		if( !$idInstitution ) {
			return $this->forwardTo('home');
		}

		try {
			/** @var InstitutionForm $institution  */
			$institution = $this->getInstitutionForEdit($idInstitution);
		} catch(\Exception $ex ) {
			$flashMessenger->addErrorMessage('InstitutionForm not found');

			return $this->forwardTo('home');
		}

		$form = $this->getInstitutionForm();
		$form->bind($institution);

		$request = $this->getRequest();
		if( $request->isPost() ) {
			$form->setInputFilter($institution->getInputFilter());
			$form->setData($request->getPost());

			if( $form->isValid() ) {
				$this->getTable()->save($form->getData());
				$flashMessenger->addSuccessMessage('InstitutionForm saved');
			} else {
				//$messages = $form->getMessages();
				$flashMessenger->addErrorMessage('Form not valid');
			}
		}

		$form->setAttribute('action', $this->makeUrl('institution', 'edit', $idInstitution));

		return $this->getAjaxView(array(
			'form'		=> $form,
			'title'		=> 'Edit Institution'
		));
	}


	protected function getInstitutionForEdit($idInstitution) {
		$institution		= $this->getTable()->getRecord($idInstitution);
		/** @var View[]	$views  */
		$views				= $this->getViews();
		/** @var InstitutionRelation[] $existingRelations  */
		$existingRelations	= $this->getTable('InstitutionRelation')->getRelations($idInstitution);
		$relations			= array();



		foreach($views as $view) {
			foreach($existingRelations as $index => $existingRelation) {
				if( $view->getId() == $existingRelation->getIdView() ) {
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

	protected function getViews() {
		$results= $this->getTable('View')->getAll(30, 'id');

		return $this->toList($results);
	}


	protected function getGroups() {
		$results	= $this->getTable('Group')->getAll();

		return $this->toList($results);
	}

	protected function toList(ResultSetInterface $set) {
		$list	= array();

		foreach($set as $item) {
			$list[] = $item;
		}

		return $list;
	}



	/**
	 *
	 *
	 * @return	InstitutionForm
	 */
	protected function getInstitutionForm() {
		$views	= $this->getViews();
		$groups	= $this->getGroups();

		/** @var InstitutionForm $form  */
		return new InstitutionForm($views, $groups);
	}

}

?>