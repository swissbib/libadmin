<?php
namespace Libadmin\Controller;

//use RecursiveIteratorIterator;

use Libadmin\Model\InstitutionRelation;
use Libadmin\Table\InstitutionRelationTable;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;
use Zend\Db\ResultSet\ResultSet;

use Libadmin\Form\InstitutionForm;
use Libadmin\Model\Institution;
use Libadmin\Controller\BaseController;
use Libadmin\Model\View;
use Libadmin\Model\Group;

/**
 * [Description]
 *
 */
class InstitutionController extends BaseController
{

	/**
	 * Add institution
	 *
	 * @return Response|ViewModel
	 */
	public function addAction()
	{
		$form			= $this->getInstitutionForm();
		$flashMessenger	= $this->flashMessenger();
		$institution	= $this->getInstitutionForAdd();

		/** @var Request $request */
		$request		= $this->getRequest();

		$form->bind($institution);

		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$institution->exchangeArray($form->getData());
				$idInstitution = $this->getTable()->save($institution);

				$flashMessenger->addSuccessMessage($this->translate('saved_institution'));

				return $this->redirectTo('edit', $idInstitution);
			} else {
				$flashMessenger->addErrorMessage($this->translate('form_invalid'));
//				$messages = $form->getMessages();
//				foreach($form->getMessages() as $message) {
//					$flashMessenger->addErrorMessage($message);
//				}
			}
		}

		$form->setAttribute('action', $this->makeUrl('institution', 'add'));

		return $this->getAjaxView(array(
			'customform' => $form,
			'title' => $this->translate('institution_add', 'Libadmin'),
		), 'libadmin/institution/edit');
	}



	/**
	 * Edit institution
	 *
	 * @return ViewModel
	 */
	public function editAction()
	{
		$idInstitution = (int)$this->params()->fromRoute('id', 0);
		$flashMessenger = $this->flashMessenger();

		if (!$idInstitution) {
			return $this->forwardTo('home');
		}

		try {
			/** @var InstitutionForm $institution */
			$institution = $this->getInstitutionForEdit($idInstitution);
		} catch (\Exception $ex) {
			$flashMessenger->addErrorMessage($this->translate('notfound_record') );

			return $this->forwardTo('home');
		}

		$form = $this->getInstitutionForm();
		$form->bind($institution);

		/** @var Request $request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getTable()->save($form->getData());
				$flashMessenger->addSuccessMessage($this->translate('saved_institution'));
				$form->bind($this->getInstitutionForEdit($idInstitution)); // Reload data
			} else {
				$flashMessenger->addErrorMessage($this->translate('form_invalid'));
//				$messages = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($form->getMessages()));
//				foreach($messages as $message) {
//					$flashMessenger->addErrorMessage($message);
//				}
			}
		}

		$form->setAttribute('action', $this->makeUrl('institution', 'edit', $idInstitution));

		return $this->getAjaxView(array(
			'customform' => $form,
			'title' => $this->translate('institution_edit', 'Libadmin'),
		));
	}



	/**
	 * Get institution prepared to be bound to the form
	 *
	 * @param   Integer        $idInstitution
	 * @return	Institution
	 */
	protected function getInstitutionForEdit($idInstitution)
	{
		$institution 		= $this->getTable()->getRecord($idInstitution);
		$views 				= $this->getAllViews();
		/** @var InstitutionRelationTable $relationTable */
		$relationTable		= $this->getTable('InstitutionRelation');
		/** @var InstitutionRelation[] $existingRelations */
		$existingRelations	= $relationTable->getInstitutionRelations($idInstitution);
		$relations			= array();

		foreach ($views as $view) {
			foreach ($existingRelations as $index => $existingRelation) {
				if ($view->getId() == $existingRelation->getIdView()) {
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



	/**
	 * Get institution prepared to be bound to the form
	 *
	 * @return	Institution
	 */
	protected function getInstitutionForAdd()
	{
		$views = $this->getAllViews();
		$institution = new Institution();
		$relations = array();

		foreach ($views as $view) {
			$relations[] = new InstitutionRelation();
		}

		$institution->setRelations($relations);

		return $institution;
	}



	/**
	 * Get institution form initialized with view and group data
	 *
	 * @return    InstitutionForm
	 */
	protected function getInstitutionForm()
	{
		$views	= $this->getAllViews();
		$groups	= $this->getGroups();

		/** @var InstitutionForm $form */
		return new InstitutionForm($views, $groups);
	}



	/**
	 * Before institution delete, remove all relations
	 *
	 * @param    Integer        $idView
	 */
	protected function beforeDelete($idView)
	{
		$this->getInstitutionRelationTable()->deleteInstitutionRelations($idView);
	}
}
