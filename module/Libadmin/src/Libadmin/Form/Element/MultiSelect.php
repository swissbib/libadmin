<?php
namespace Libadmin\Form\Element;

/**
 * [Description]
 *
 */
class MultiSelect extends NoValidationSelect
{

	public function __construct($name = null, $options = array())
	{
		parent::__construct($name, $options);

		$this->setAttributes(array(
								  'multiple'	=> true,
								  'size'		=> 10
							 ));
	}

}
