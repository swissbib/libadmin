<?php
namespace Libadmin\Form;

use Zend\Form\Form;

/**
 * Base form with convenience helpers
 *
 */
class BaseForm extends Form {

	public function __construct($name = null, $options = array()) {
		parent::__construct($name, $options);

		$this->setAttribute('method', 'post');
	}



	/**
	 * Add hidden field
	 *
	 * @param	String		$name
	 */
	protected function addHidden($name) {
		$this->add(array(
			'name' => $name,
			'attributes' => array(
				'type'  => 'hidden',
			),
		));
	}



	/**
	 * Add text field
	 *
	 * @param	String		$name
	 * @param	String		$label
	 */
	protected function addText($name, $label, $required = false) {
		$this->add(array(
			'name' => $name,
			'attributes' => array(
				'type'  => 'text',
				'required'	=> !!$required
			),
			'options' => array(
				'label' => $label
			),
		));
	}



	/**
	 * Add submit button
	 *
	 * @param $label
	 * @param string $name
	 * @param string $id
	 */
	protected function addSubmit($label, $name = 'submit', $id = 'submitbutton') {
		$this->add(array(
			'name' => $name,
			'attributes' => array(
				'type'  => 'submit',
				'value'	=> $label,
				'id'	=> $id,
				'class'	=> 'submitButton btn btn-primary btn-medium'
			)
		));
	}

}

?>