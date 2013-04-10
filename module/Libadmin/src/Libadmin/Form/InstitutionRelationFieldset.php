<?php
namespace Libadmin\Form;

use Libadmin\Model\InstitutionRelation;
use Libadmin\Model\View;
use Zend\Form\Element\Checkbox;
use Zend\Form\Fieldset;
use Zend\Form\FormInterface;
use Zend\Stdlib\Hydrator\ArraySerializable as ArrayHydrator;
use Zend\Stdlib\Hydrator\ClassMethods  as ClassMethodsHydrator;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;


/**
 * [Description]
 *
 */
class InstitutionRelationFieldset extends Fieldset {




	public function __construct() {
		parent::__construct('link');

		$this->setHydrator(new ObjectPropertyHydrator());
		$this->setObject(new InstitutionRelation());

		$this->add(array(
			'name'	=> 'id_view',
            'type'  => 'checkbox',
            'options'   => array(
                'label' => 'Name Test'
            ),
			'required'	=> false
		));

		$this->add(array(
			'name' => 'id_group',
			'type' => 'select',
			'options' => array(
				'label' => 'Gruppe',
				'value_options' => array(
					'1'	=> 'Test 1',
					'2'	=> 'Test 2',
					'3'	=> 'Test 3'
				)
			)
		));

        $this->add(array(
            'name'	=> 'is_favorite',
            'type'  => 'checkbox',
            'options'   => array(
                'label' => 'Ist Favorit'
            ),
			'required'	=> false
        ));
	}



	public function prepareElement(FormInterface $form) {
		parent::prepareElement($form);

		/** @var Checkbox $viewCheckbox */
		$viewCheckbox	= $this->byName['id_view'];

		/** @var View $view  */
		$view	= $form->views->current();

		$viewCheckbox->setCheckedValue($view->getId());
		$viewCheckbox->setLabel($view->getLabel());
	}

	public function populateValues($data) {
		$this->elements['id_view']->setUncheckedValue($data['id_view']);
		$this->elements['is_favorite']->setUncheckedValue($data['is_favorite']);

		parent::populateValues($data);
	}


}

?>