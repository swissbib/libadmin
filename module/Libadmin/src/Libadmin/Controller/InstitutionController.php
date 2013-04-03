<?php
namespace Libadmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Libadmin\Form\InstitutionForm;
use Libadmin\Model\Institution;
use Libadmin\Model\InstitutionTable;
use Libadmin\Controller\BaseController;


/**
 * [Description]
 *
 */
class InstitutionController extends BaseController {

	/**
	 * @var InstitutionTable
	 */
	protected $institutionTable;


	public function indexAction() {
		return array(
			'searchResults' => $this->getInstitutionTable()->getAll(30)
		);
	}


	public function homeAction() {
		return array();
	}


	public function addAction() {
		$form = new InstitutionForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if( $request->isPost() ) {
			$institution = new Institution();
			$form->setInputFilter($institution->getInputFilter());
			$form->setData($request->getPost());

			if( $form->isValid() ) {
				$institution->exchangeArray($form->getData());
				$idInstitution	= $this->getInstitutionTable()->saveInstitution($institution);

				$this->flashMessenger()->addMessage('New institution added');

				return $this->forwardTo('edit', array(
					'id' => $idInstitution
				));
			}
		}

		$form->setAttribute('action', $this->url()->fromRoute(
		    'institution',
		    array(
				'action' => 'add'
		    )
		));

		$view = $this->getAjaxView(array(
			'form'	=> $form,
			'title'	=> 'Add Institution'
		));

		$view->setTemplate('libadmin/institution/edit');

		return $view;
	}



	public function editAction() {
		$idInstitution = (int)$this->params()->fromRoute('id', 0);
		if( !$idInstitution ) {
			return $this->forwardTo('home');
		}


		try {
			$institution = $this->getInstitutionTable()->getInstitution($idInstitution);
		} catch( \Exception $ex ) {
			$this->flashMessenger()->addMessage('Institution not found');

			return $this->forwardTo('home');
//			return $this->redirect()->toRoute('institution', array(
//				'action' => 'index'
//			));
		}

		$form = new InstitutionForm();
		$form->bind($institution);
		$form->get('submit')->setAttribute('value', 'Update');

		$request = $this->getRequest();
		if( $request->isPost() ) {
			$form->setInputFilter($institution->getInputFilter());
			$form->setData($request->getPost());

			if( $form->isValid() ) {
				$this->getInstitutionTable()->saveInstitution($form->getData());

				// Redirect to list of albums
//				return $this->redirect()->toRoute('album');
			}
		}

		$form->setAttribute('action', $this->url()->fromRoute(
			'institution',
			array(
				'action' => 'add'
			)
		));

		return $this->getAjaxView(array(
			'form'	=> $form,
			'title'	=> 'Edit Institution'
		));
	}



	public function searchAction() {
		return $this->getAjaxView();

	}



	protected function getAjaxView($variables = array()) {
		$viewModel = new ViewModel($variables);
		$viewModel->setTerminal(true);

		return $viewModel;
	}



	protected function getInstitutionTable() {
		if( !$this->institutionTable ) {
			$this->institutionTable = $this->getServiceLocator()->get('Libadmin\Model\InstitutionTable');
		}
		return $this->institutionTable;
	}

}

?>