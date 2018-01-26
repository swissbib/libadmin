<?php
namespace Libadmin\Form;

use Zend\Db\ResultSet\ResultSet;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;

use Libadmin\Form\Element\NoValidationMultiCheckbox;
use Libadmin\Model\View;
use Libadmin\Table\ViewTable;
use Libadmin\Model\Group;
use Libadmin\Form\BaseFieldset;
use Zend\Form\Fieldset;

/**
 * [Description]
 *
 */
class GroupFieldset extends BaseFieldset implements InputFilterProviderInterface
{
	/** @var View[] */
	protected $views;

	public function __construct(array $views, $options = array())
	{
		parent::__construct('group', $options);

		$this->setHydrator(new ClassMethodsHydrator(false))
						->setObject(new Group);

		$this->views = $views;

		$this->addHidden('id');
		$this->addText('code', 'Code', true);
		$this->add(array(
			'name' => 'is_active',
			'type' => 'checkbox',
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
		if (sizeof($this->views)) {
			$viewOptions = array();
			foreach ($this->views as $view) {
				/** @var View $view */
				$viewOptions[$view->getID()] = $view->getLabel();
			}
			$viewCheckboxes = new NoValidationMultiCheckbox('views');
			$viewCheckboxes->setValueOptions($viewOptions);
			$viewCheckboxes->setUncheckedValue(0);
			$this->add($viewCheckboxes);
		}

		$this->addInstitutionRelations();
	}



	/**
	 * Add relations based on views
	 *
	 */
	protected function addInstitutionRelations()
	{
		$this->add(array(
			'type' => 'Zend\Form\Element\Collection',
			'name' => 'relations',
			'options' => array(
				'count' => sizeof($this->views),
				'target_element' => array(
					'type' => 'Libadmin\Form\GroupRelationFieldset'
				)
			)
		));
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
			'label_de' => array(
				'required' => true
			),
			'label_fr' => array(
				'required' => true
			),
			'label_it' => array(
				'required' => true
			),
			'label_en' => array(
				'required' => true
			),
			'view' => array(
				'required' => false
			),
			'relations' => array(
				'required'	=> false
			)
		);
	}
}
