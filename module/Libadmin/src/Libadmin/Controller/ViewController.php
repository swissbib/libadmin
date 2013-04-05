<?php
namespace Libadmin\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Libadmin\Table\ViewTable;


/**
 * [Description]
 *
 */
class ViewController extends BaseController {

	/**
	 * @return    ViewTable
	 */
	protected function getTable() {
		if( !$this->table ) {
			$this->table = $this->getServiceLocator()->get('Libadmin\Table\ViewTable');
		}
		return $this->table;
	}

}

?>