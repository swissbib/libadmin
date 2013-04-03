<?php
namespace Libadmin\Form;

use Zend\Form\Form;

/**
 * [Description]
 *
 */
class BaseForm extends Form {

	protected function addHidden($name) {
		$this->add(array(
			'name' => $name,
			'attributes' => array(
				'type'  => 'hidden',
			),
		));
	}

	protected function addText($name, $label) {
		$this->add(array(
			'name' => $name,
			'attributes' => array(
				'type'  => 'text',
			),
			'options' => array(
				'label' => $label
			),
		));
	}

	protected function addSubmit($label, $name = 'submit', $id = 'submitbutton') {
		$this->add(array(
			'name' => $name,
			'attributes' => array(
				'type'  => 'submit',
				'value' => $label,
				'id' => $id,
			),
		));
	}

}

?>