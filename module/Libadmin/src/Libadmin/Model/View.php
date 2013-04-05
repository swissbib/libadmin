<?php
namespace Libadmin\Model;

use Libadmin\Model\BaseModel;

/**
 * Class View
 * @package Libadmin\Model
 */
class View extends BaseModel {

	public $code;
	public $is_active;
	public $label;
	public $notes;



	/**
	 * @return string
	 */
	public function getListLabel() {
		return $this->code . ': ' . $this->label;
	}

	public function getTypeLabel() {
		return 'View';
	}

}

?>