<?php
namespace Libadmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Libadmin\Table\BaseTable;


/**
 * [Description]
 *
 */
abstract class BaseController extends AbstractActionController {

	/**
	 * @var BaseTable
	 */
	protected $table;


	public function searchAction() {
		$query = $this->params()->fromQuery('query', '');
		$data = array(
			'route' => strtolower($this->getTypeName()),
			'listItems' => $this->getTable()->find($query, 30)
		);

		return $this->getAjaxView($data, 'libadmin/global/search');
	}



	protected function getTypeName() {
		$nameParts	= explode('\\', get_class($this));

		return str_replace('Controller', '', array_pop($nameParts));
	}

	protected function forwardTo($action, array $params = array()) {
		$name		= $this->getTypeName();
		$params		= array_merge_recursive(array(
			'action'	=> $action
		), $params);

		return $this->forward()->dispatch('Libadmin\Controller\\' . $name, $params);
	}

	protected function getAjaxView($variables = array(), $template = '', $noMessages = false) {
		if( !$noMessages ) {
			$flashMessenger	= $this->flashMessenger();
			$variables		= array_merge(array(
				'messages'	=> array(
					'error'		=> $flashMessenger->getErrorMessages(),
					'info'		=> $flashMessenger->getInfoMessages(),
					'success'	=> $flashMessenger->getSuccessMessages()
				)
			), $variables);
		}

		$viewModel = new ViewModel($variables);
		$viewModel->setTerminal(true);

		if( $template ) {
			$viewModel->setTemplate($template);
		}



		return $viewModel;
	}



	/**
	 *
	 * @param	String		$route
	 * @param	String		$action
	 * @param	Integer		$idElement
	 * @param	Array		$additionalParams
	 * @return	String
	 */
	protected function makeUrl($route, $action, $idElement = 0, array $additionalParams = array()) {
		$params	= array(
			'action'	=> $action
		);

		if( $idElement ) {
			$params['id'] = $idElement;
		}
		$params = array_merge($params, $additionalParams);

		return $this->url()->fromRoute($route, $params);
	}



	/**
	 * @return	BaseTable
	 */
	protected abstract function getTable();

}

?>