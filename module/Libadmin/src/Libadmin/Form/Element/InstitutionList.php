<?php
namespace Libadmin\Form\Element;

use Libadmin\Model\InstitutionRelation;

/**
 * [Description]
 *
 */
class InstitutionList extends MultiSelect
{

	public function __construct($name = null, $options = array())
	{
		parent::__construct($name, $options);

		$this->disableInArrayValidator = true;

		$this->setAttribute('size', 20);
	}



	public function setValue($value)
	{
		$options = array();

		if (is_array($value)) {
			if (isset($value['relations'])) {
				$first	= reset($value['relations']);

				if ($first instanceof InstitutionRelation) {
					/** @var InstitutionRelation $institutionRelation */
					foreach ($value['relations'] as $institutionRelation) {
						$options[] = $institutionRelation->getIdInstitution();
					}
				}
			} else {
				$options = $value;
			}
		}

		return parent::setValue($options);
	}
}
