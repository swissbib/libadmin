<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/13/12
 * Time: 2:58 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Libadmin\Model;

//use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
//use Zend\InputFilter\InputFilter;                 // <-- Add this import
//use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
//use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

use Libadmin\Model\BaseModel;

/**
 * Group
 *
 * @package	Libadmin\Model
 */
class Group extends BaseModel {

	public $code;
	public $is_active;

	public $label_de;
	public $label_fr;
	public $label_it;
	public $label_en;

	public $notes;



	/**
	 * Get listing label
	 *
	 * @return	String
	 */
	public function getListLabel() {
		return $this->code . ': ' . $this->label_de;
	}

	public function getTypeLabel() {
		return 'Group';
	}


}
