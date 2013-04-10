<?php
namespace Libadmin\Form;

use Libadmin\Form\BaseForm;
use Libadmin\Table\GroupTable;
use Zend\Form\Element;
use Zend\Form\Fieldset;


/**
 * [Description]
 *
 */
class ViewForm extends BaseForm {

	/**
	 * @var	GroupTable
	 */
	protected $groupTable;



	/**
	 * Initialize
	 *
	 * @param	GroupTable		$groupTable
	 * @param	String|Null		$name
	 * @param	Array			$options
	 */
	public function __construct(GroupTable $groupTable, $name = null, $options = array()) {
		parent::__construct('view', $options);

		$this->groupTable	= $groupTable;

		$this->addHidden('id');

		$this->addText('code', 'Code', true);
		$this->addText('label', 'Name', true);

		$this->add(array(
			'name'		=> 'is_active',
			'type' 		=> 'checkbox',
			'options'	=> array(
				'label' => 'is_active'
			)
		));

		$this->add(array(
			'name'	=> 'notes',
			'type'  => 'textarea',
			'options' => array(
				'label' => 'internal_notes'
			),
			'attributes' => array(
				'rows'	=> 10
			)
		));

			// @todo wrap in a method or a field type
			// Make not required
		$allGroups	= $this->groupTable->getAll();
		if( $allGroups ) {
			$groupOptions= array();
			foreach($allGroups as $group) {
				$groupOptions[$group->getID()] = $group->getCode();
			}
			$groupCheckboxes = new Element\MultiCheckbox('groups');
			$groupCheckboxes->setValueOptions($groupOptions);
	//		$groupCheckboxes->setOptions(array(
	//			'required'	=> false
	//		));
			$this->add($groupCheckboxes);
		}

	}

}

?>






