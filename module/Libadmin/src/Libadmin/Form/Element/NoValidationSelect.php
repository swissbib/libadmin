<?php
namespace Libadmin\Form\Element;

use Zend\Form\Element\Select as BaseSelect;

/**
 * [Description]
 *
 */
class NoValidationSelect extends BaseSelect {

	public function getInputSpecification() {
		$spec = parent::getInputSpecification();

		unset($spec['required']);
		unset($spec['validators']);

		return $spec;
	}

}

?>