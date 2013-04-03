<?php
namespace Libadmin\Controller;

use Libadmin\Form\InstitutionForm;
use Libadmin\Model\Institution;
use Libadmin\Model\InstitutionTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * [Description]
 *
 */
class InstitutionController extends AbstractActionController {

	/**
	 * @var InstitutionTable
	 */
	protected $institutionTable;


	public function indexAction() {
		return array(
			'searchResults' => $this->getInstitutionTable()->getAll(30)
		);
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
				$this->getInstitutionTable()->saveInstitution($institution);

				// Redirect to list of albums
				return $this->redirect()->toRoute('institution');
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
//		$id = (int)$this->params()->fromRoute('id', 0);
//		if( !$id ) {
//			return $this->redirect()->toRoute('album', array(
//				'action' => 'add'
//			));
//		}
//
//		// Get the Album with the specified id.  An exception is thrown
//		// if it cannot be found, in which case go to the index page.
//		try {
//			$album = $this->getAlbumTable()->getAlbum($id);
//		} catch( \Exception $ex ) {
//			return $this->redirect()->toRoute('album', array(
//				'action' => 'index'
//			));
//		}
//
//		$form = new AlbumForm();
//		$form->bind($album);
//		$form->get('submit')->setAttribute('value', 'Edit');
//
//		$request = $this->getRequest();
//		if( $request->isPost() ) {
//			$form->setInputFilter($album->getInputFilter());
//			$form->setData($request->getPost());
//
//			if( $form->isValid() ) {
//				$this->getAlbumTable()->saveAlbum($form->getData());
//
//				// Redirect to list of albums
//				return $this->redirect()->toRoute('album');
//			}
//		}
//
//		return $this->getAjaxView(array(
//			'id' => $id,
//			'form' => $form,
//		));
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