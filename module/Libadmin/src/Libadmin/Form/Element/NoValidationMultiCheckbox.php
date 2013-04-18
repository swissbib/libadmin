<?php
namespace Libadmin\Form\Element;

use Zend\Form\Element\MultiCheckbox;

/**
 * [Description]
 *
 */
class NoValidationMultiCheckbox extends MultiCheckbox
{

	/**
	 * @return    Array
	 */
	public function getInputSpecification()
	{
		$spec = parent::getInputSpecification();

		$spec['required'] = false;

		unset($spec['validators']);

		return $spec;
	}
}
