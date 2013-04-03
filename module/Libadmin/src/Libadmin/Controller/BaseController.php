<?php
namespace Libadmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * [Description]
 *
 */
class BaseController extends AbstractActionController {

	protected function forwardTo($action, array $params = array()) {
		$nameParts	= explode('\\', get_class($this));
		$name		= str_replace('Controller', '', array_pop($nameParts));
		$params		= array_merge_recursive(array(
			'action'	=> $action
		), $params);

		return $this->forward()->dispatch('Libadmin\Controller\\' . $name, $params);
	}
}

?>