<?php
namespace Libadmin\Form;

use Zend\Form\Form;

/**
 * Base form with convenience helpers
 *
 */
class BaseForm extends Form {

	/**
	 * Add hidden field
	 *
	 * @param	String		$name
	 */
	protected function addHidden($name) {
		$this->add(array(
			'name' => $name,
			'attributes' => array(
				'type'  => 'hidden'
			)
		));
	}



	/**
	 * Add text field
	 *
	 * @param	String		$name
	 * @param	String		$label
	 * @param	Boolean		$required
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
			)
		));
	}

}

?>