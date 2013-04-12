<?php
namespace Libadmin\Form\Element;

use Zend\Form\Element\Checkbox as BaseCheckbox;

/**
 * [Description]
 *
 */
class NoValidationCheckbox extends BaseCheckbox {

	public function getInputSpecification() {
		$spec = parent::getInputSpecification();

		unset($spec['required']);
		unset($spec['validators']);

		return $spec;
	}

}