<?php
namespace Libadmin\Form;

use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Element;
use Zend\InputFilter\InputFilterProviderInterface;

use Libadmin\Form\BaseForm;
use Libadmin\Table\ViewTable;
use Libadmin\Model\Group;
use Libadmin\Form\Element\NoValidationMultiCheckbox;

/**
 * Group form
 *
 */
class GroupForm extends BaseForm implements InputFilterProviderInterface {

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
		$this->addText('label_de', 'language_german');
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
			$viewCheckboxes	= new NoValidationMultiCheckbox('views');
			$viewCheckboxes->setValueOptions($viewOptions);
			$viewCheckboxes->setUncheckedValue(0);
			$this->add($viewCheckboxes);
		}
	}



	/**
	 * Set data
	 * Make sure views data is always set
	 *
	 * @param	Array|\ArrayAccess|\Traversable	$data
	 * @return	GroupForm
	 */
	public function setData($data) {
		if( !isset($data['views']) ) {
			$data['views'] = array();
		}

		return parent::setData($data);
	}



	/**
	 * Get input filters and validations
	 *
	 * @return	Array
	 */
	public function getInputFilterSpecification() {
		return array(
			'code' => array(
				'required'	=> true,
				'filters'	=> array(
					array('name'=> 'StringTrim')
				)
			),
			'label_de' => array(
				'required'	=> true
			),
			'label_fr' => array(
				'required'	=> true
			),
			'label_it' => array(
				'required'	=> true
			),
			'label_en' => array(
				'required'	=> true
			),
			'view'	=> array(
				'required'	=> false
			)
		);
	}

}






