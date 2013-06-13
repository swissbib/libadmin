<?php
namespace Libadmin\Controller;

use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

use Libadmin\Table\BaseTable;
use Libadmin\Table\GroupTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\ViewTable;
use Libadmin\Model\BaseModel;
use Libadmin\Model\Group;
use Libadmin\Model\View;
use Libadmin\Model\Institution;
use Libadmin\Table\GroupRelationTable;
use Libadmin\Table\InstitutionRelationTable;

/**
 * [Description]
 *
 */
abstract class BaseController extends AbstractActionController
{

	/**
	 * @var BaseTable
	 */
	protected $table;


	protected $translator;



	/**
	 * Initial view
	 *
	 * @return array
	 */
	public function indexAction()
	{
		return array(
			'listItems' => $this->getTable()->getAll(null, 15)
		);
	}



	/**
	 * Home view
	 *
	 * @return    ViewModel
	 */
	public function homeAction()
	{
		return $this->getAjaxView(
			array(
				 'listItems' => $this->getTable()->getAll()
			));
	}



	/**
	 * Search matching records
	 *
	 * @param	Integer		$limit        Search result limit
	 * @return	ViewModel
	 **/
	public function searchAction($limit = 15)
	{
		$query = $this->params()->fromQuery('query', '');
		$data = array(
			'route' => strtolower($this->getTypeName()),
			'listItems' => $this->getTable()->find($query, $limit)
		);

		return $this->getAjaxView($data, 'libadmin/global/search');
	}



	/**
	 * Delete record or show delete confirmation form
	 *
	 * @return    Response|ViewModel
	 */
	public function deleteAction()
	{
		$idRecord = (int)$this->params()->fromRoute('id', 0);

		if (!$idRecord) {
			$this->flashMessenger()->addErrorMessage('No record defined for deletion. Something went wrong');

			return $this->redirectTo('home');
		}

		/** @var Request $request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			$isDeleteRequest = $request->getPost('del') !== null;

			if ($isDeleteRequest) {
				$idRecord = (int)$request->getPost('id');
				$this->beforeDelete($idRecord);
				$this->getTable()->delete($idRecord);
				$this->afterDelete($idRecord);
				// @todo message is shown to late, solve this problem and re-enable message
				//	$this->flashMessenger()->addSuccessMessage('Record deleted');
			}

			return $this->redirectTo('home');
		}

		return $this->getAjaxView(array(
									   'id' => $idRecord,
									   'route' => $this->getRouteName(),
									   'record' => $this->getTable()->getRecord($idRecord)
								  ), 'libadmin/global/delete');
	}



	/**
	 * Template method which is run before record delete
	 *
	 * @param    Integer $idRecord
	 */
	protected function beforeDelete($idRecord)
	{

	}



	/**
	 * Template method which is run after record delete
	 *
	 * @param    Integer $idRecord
	 */
	protected function afterDelete($idRecord)
	{

	}



	/**
	 * Get type name of class from the class name
	 *
	 * @return    String
	 */
	protected function getTypeName()
	{
		$nameParts = explode('\\', get_class($this));

		return str_replace('Controller', '', array_pop($nameParts));
	}



	/**
	 * Get route name (same as class type in lowercase)
	 *
	 * @return    String
	 */
	protected function getRouteName()
	{
		return strtolower($this->getTypeName());
	}



	/**
	 * Forward to other controller action
	 *
	 * @param    String $action
	 * @param    Array $params
	 * @return    ViewModel
	 */
	protected function forwardTo($action, array $params = array())
	{
		$name = $this->getTypeName();
		$params = array_merge_recursive(array(
											 'action' => $action
										), $params);

		return $this->forward()->dispatch('Libadmin\Controller\\' . $name, $params);
	}



	/**
	 * Redirect to other controller action
	 *
	 * @param    String $action
	 * @param    Integer $idRecord
	 * @return    Response
	 */
	protected function redirectTo($action = '', $idRecord = 0)
	{
		$route = strtolower($this->getTypeName());
		$params = array();

		if ($action) {
			$params['action'] = $action;
		}

		if ($idRecord) {
			$params['id'] = $idRecord;
		}

		return $this->redirect()->toRoute($route, $params);
	}



	/**
	 * Get terminal view model for ajax
	 *
	 * @param    Array $variables
	 * @param    String $template
	 * @return    ViewModel
	 */
	protected function getAjaxView($variables = array(), $template = '')
	{
		$viewModel = new ViewModel($variables);
		$viewModel->setTerminal(true);

		if ($template) {
			$viewModel->setTemplate($template);
		}

		return $viewModel;
	}



	/**
	 * Make url based on route
	 * Add action and element id to route if specified
	 *
	 * @param    String $route
	 * @param    String $action
	 * @param    Integer $idElement
	 * @param    Array $additionalParams
	 * @return    String
	 */
	protected function makeUrl($route, $action, $idElement = 0, array $additionalParams = array())
	{
		$params = array(
			'action' => $action
		);

		if ($idElement) {
			$params['id'] = $idElement;
		}
		$params = array_merge($params, $additionalParams);

		return $this->url()->fromRoute($route, $params);
	}



	/**
	 * Get table
	 *
	 * @param    String|Null $type
	 * @return    InstitutionTable|GroupTable|ViewTable
	 */
	protected function getTable($type = null)
	{
		if (!is_null($type)) {
			return $this->getServiceLocator()->get('Libadmin\Table\\' . ucfirst($type) . 'Table');
		}

		if (!$this->table) {
			$type = $this->getTypeName();
			$this->table = $this->getServiceLocator()->get('Libadmin\Table\\' . $type . 'Table');
		}
		return $this->table;
	}



	/**
	 * Translate key
	 *
	 * @param    String $key
	 * @param    String $domain
	 * @return    String
	 */
	protected function translate($key, $domain = 'Libadmin')
	{
		if (null == $this->translator) {
			$this->translator = $this->getServiceLocator()->get('translator');
		}

		return $this->translator->translate($key, $domain);
	}



	/**
	 * Extract all result items from a result set to work with a simple list
	 *
	 * @param    ResultSetInterface $set
	 * @param    Boolean $idAsIndex
	 * @return    BaseModel[]
	 */
	protected function toList(ResultSetInterface $set, $idAsIndex = false)
	{
		$list = array();

		/** @var BaseModel $item */
		foreach ($set as $item) {
			if ($idAsIndex) {
				$list[$item->getId()] = $item;
			} else {
				$list[] = $item;
			}
		}

		return $list;
	}



	/**
	 * Get all views
	 *
	 * @return    View[]
	 */
	protected function getAllViews()
	{
		$results = $this->getTable('View')->getAll('id', 0);

		return $this->toList($results);
	}



	/**
	 * Get groups
	 *
	 * @param    String $order
	 * @return    \Libadmin\Model\BaseModel[]
	 */
	protected function getGroups($order = null)
	{
		$results = $this->getTable('Group')->getAll();

		return $this->toList($results);
	}



	/**
	 * Get institutions
	 *
	 * @param    String|Null $order
	 * @param    Integer $limit
	 * @return    Institution[]
	 */
	protected function getInstitutions($order = 'bib_code', $limit = 30)
	{
		$results = $this->getTable('Institution')->getAll($order, $limit);

		return $this->toList($results, true);
	}



	/**
	 * Get institution relation table
	 *
	 * @return    InstitutionRelationTable
	 */
	protected function getInstitutionRelationTable()
	{
		return $this->getServiceLocator()->get('Libadmin\Table\InstitutionRelationTable');
	}



	/**
	 * Get group relation table
	 *
	 * @return    GroupRelationTable
	 */
	protected function getGroupRelationTable()
	{
		return $this->getServiceLocator()->get('Libadmin\Table\GroupRelationTable');
	}
}
