<?php
namespace Libadmin\Form;

use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Element;

use Libadmin\Form\BaseForm;
use Libadmin\Table\ViewTable;
use Libadmin\Model\Group;

/**
 * Group form
 *
 */
class GroupForm extends BaseForm {

	/**
	 * @var	ViewTable	To access views
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
				'label' => 'is_active'
			)
		));
		$this->addText('label_de', 'language_german', true);
		$this->addText('label_fr', 'language_french', true);
		$this->addText('label_it', 'language_italian', true);
		$this->addText('label_en', 'language_english', true);

		$this->add(array(
			'name' => 'notes',
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
		$allViews	= $viewTable->getAll();
		if( sizeof($allViews) ) {
			$viewOptions= array();
			foreach($allViews as $view) {
				$viewOptions[$view->getID()] = $view->getLabel();
			}
			$viewCheckboxes	= new Element\MultiCheckbox('views');
			$viewCheckboxes->setValueOptions($viewOptions);
//			$viewCheckboxes->setOptions(array(
//				'required'	=> false
//			));
			$this->add($viewCheckboxes);
		}
	}

}

?>






