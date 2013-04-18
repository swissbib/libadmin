<?php
namespace Libadmin\Form\Element;

use Zend\Form\Element\Checkbox as BaseCheckbox;

/**
 * [Description]
 *
 */
class NoValidationCheckbox extends BaseCheckbox
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
