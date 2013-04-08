<?php
namespace Libadmin\Form;

use Libadmin\Form\BaseForm;
use Libadmin\Table\ViewTable;
use Zend\Form\Element;
use Zend\Form\Fieldset;

use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Libadmin\Model\Group;
//use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;

/**
 * [Description]
 *
 */
class GroupForm extends BaseForm {

	/**
	 * @var	ViewTable
	 */
	protected $viewTable;



	/**
	 * Initialize
	 * Inject view table
	 *
	 * @param	ViewTable		$viewTable
	 * @param	String|Null		$name
	 * @param	Array			$options
	 */
	public function __construct(ViewTable $viewTable, $name = null, $options = array()) {
		parent::__construct('group', $options);

		$this->viewTable = $viewTable;

		$this->setHydrator(new ClassMethodsHydrator(false));
		$this->setObject(new Group);

		$this->addHidden('id');
		$this->addText('code', 'Code', true);
		$this->add(array(
			'name' => 'is_active',
			'type'  => 'checkbox',
			'options' => array(
				'label' => 'Ist Aktiv'
			)
		));
		$this->addText('label_de', 'deutsch', true);
		$this->addText('label_fr', 'französisch');
		$this->addText('label_it', 'italienisch');
		$this->addText('label_en', 'englisch');

		$this->add(array(
			'name' => 'notes',
			'type'  => 'textarea',
			'options' => array(
				'label' => 'Interne Notizen'
			),
			'attributes' => array(
				'rows'	=> 10
			)
		));


		$allViews	= $viewTable->getAll();
		$viewCheckboxes	= array(
			'type' => 'multiCheckbox',
			'name' => 'views',
			'required' => false,
			'options' => array(
				'value_options' => array()
			)
		);
		foreach($allViews as $view) {
			$viewCheckboxes['options']['value_options'][$view->getID()] = $view->getLabel();
		}
		$this->add($viewCheckboxes);
	}

}

?>






