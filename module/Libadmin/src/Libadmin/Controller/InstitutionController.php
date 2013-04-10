<?php
namespace Libadmin\Controller;

use Libadmin\Model\InstitutionRelation;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

use Libadmin\Form\InstitutionForm;
use Libadmin\Model\Institution;
use Libadmin\Table\InstitutionTable;
use Libadmin\Controller\BaseController;


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
		$views			= $this->getTable('View')->getAll();
		$form			= new InstitutionForm($views);
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
			$institution = $this->getInstitution($idInstitution);
		} catch(\Exception $ex ) {
			$flashMessenger->addErrorMessage('InstitutionForm not found');

			return $this->forwardTo('home');
		}

		$views= $this->getTable('View')->getAll();
		$form = new InstitutionForm($views);
		$form->bind($institution);

		$request = $this->getRequest();
		if( $request->isPost() ) {
			$form->setInputFilter($institution->getInputFilter());
			$form->setData($request->getPost());

			if( $form->isValid() ) {
				$this->getTable()->save($form->getData());
				$flashMessenger->addSuccessMessage('InstitutionForm saved');
			} else {
				$flashMessenger->addErrorMessage('Form not valid');
			}
		}

		$form->setAttribute('action', $this->makeUrl('institution', 'edit', $idInstitution));

		return $this->getAjaxView(array(
			'form'		=> $form,
			'title'		=> 'Edit InstitutionForm'
		));
	}


	protected function getInstitution($idInstitution) {
		$institution = $this->getTable()->getRecord($idInstitution);

		$institution->setRelations($this->getTable('InstitutionRelation')->getRelations($idInstitution));

		return $institution;
	}

}

?>