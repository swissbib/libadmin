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
	 * Get list label
	 *
	 * @return	String
	 */
	public function getListLabel() {
		return $this->code . ': ' . $this->label;
	}



	/**
	 * Get type label
	 *
	 * @return	String
	 */
	public function getTypeLabel() {
		return 'View';
	}


	public function getLabel() {
		return $this->label;
	}

}

?>