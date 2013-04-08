<?php
namespace Libadmin\Controller;

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
		$form			= new InstitutionForm();
		$request		= $this->getRequest();
		$flashMessenger	= $this->flashMessenger();

		if( $request->isPost() ) {
			$institution = new Institution();
			$form->setInputFilter($institution->getInputFilter());
			$form->setData($request->getPost());

			if( $form->isValid() ) {
				$institution->exchangeArray($form->getData());
				$idInstitution	= $this->getTable()->save($institution);

				$flashMessenger->addSuccessMessage('New institution added');

				return $this->redirectTo('edit', $idInstitution);
			} else {
				$flashMessenger->addErrorMessage('Form not valid');
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
			/** @var Institution $institution  */
			$institution = $this->getTable()->getRecord($idInstitution);
		} catch(\Exception $ex ) {
			$flashMessenger->addErrorMessage('Institution not found');

			return $this->forwardTo('home');
		}

		$form = new InstitutionForm();
		$form->bind($institution);

		$request = $this->getRequest();
		if( $request->isPost() ) {
			$form->setInputFilter($institution->getInputFilter());
			$form->setData($request->getPost());

			if( $form->isValid() ) {
				$this->getTable()->save($form->getData());
				$flashMessenger->addSuccessMessage('Institution saved');
			} else {
				$flashMessenger->addErrorMessage('Form not valid');
			}
		}

		$form->setAttribute('action', $this->makeUrl('institution', 'edit', $idInstitution));

		return $this->getAjaxView(array(
			'form'		=> $form,
			'title'		=> 'Edit Institution'
		));
	}

}

?>