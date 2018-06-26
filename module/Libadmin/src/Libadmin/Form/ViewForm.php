<?php
namespace Libadmin\Form;

use Libadmin\Form\Element\NoValidationMultiCheckbox;
use Libadmin\Model\Group;
use Zend\Form\Element;
use Zend\InputFilter\InputFilterProviderInterface;

use Libadmin\Form\BaseForm;
use Libadmin\Table\GroupTable;

/**
 * [Description]
 *
 */
class ViewForm extends BaseForm implements InputFilterProviderInterface
{

	/**
	 * @var    GroupTable
	 */
	protected $groupTable;



	/**
	 * Initialize
	 *
	 * @param    GroupTable        $groupTable
	 * @param    String|Null        $name
	 * @param    Array            $options
	 */
	public function __construct(GroupTable $groupTable, $name = null, $options = array())
	{
		parent::__construct('view', $options);

		$this->groupTable = $groupTable;

		$this->addHidden('id');

		$this->addText('code', 'Code', true);
		$this->addText('label', 'Name', true);

        $this->addCheckbox('is_active', 'is_active');

		$this->add(array(
			'name' => 'notes',
			'type' => 'textarea',
			'options' => array(
				'label' => 'internal_notes'
			),
			'attributes' => array(
				'rows' => 10
			)
		));

		// @todo wrap in a method or a field type
		// Make not required
		$allGroups = $this->groupTable->getAll();
		if ($allGroups->count()) {
			$groupOptions = array();
			foreach ($allGroups as $group) {
				/** @var Group $group */
				$groupOptions[$group->getID()] = $group->getCode();
			}
			$groupCheckboxes = new NoValidationMultiCheckbox('groups');
			$groupCheckboxes->setValueOptions($groupOptions);
			$groupCheckboxes->setLabel('groups');
			$this->add($groupCheckboxes);
		}
	}



	/**
	 * Get input filters and validations
	 *
	 * @return    Array
	 */
	public function getInputFilterSpecification()
	{
		return array(
			'code' => array(
				'required' => true,
				'filters' => array(
					array('name' => 'StringTrim')
				)
			),
			'label' => array(
				'required' => true
			)
		);
	}
}
