<?php
namespace Libadmin\Form;

use Libadmin\Model\Group;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Select;
use Zend\Form\Fieldset;
use Zend\Form\FormInterface;
use Zend\Hydrator\ObjectProperty as ObjectPropertyHydrator;

use Libadmin\Model\InstitutionRelation;
use Libadmin\Model\View;

/**
 * Institution relation fieldset
 *
 */
class InstitutionRelationFieldset extends Fieldset
{

	/**
	 * Initialize
	 *
	 */
	public function __construct()
	{
		parent::__construct('relations');

		$this->setHydrator(new ObjectPropertyHydrator());
		$this->setObject(new InstitutionRelation());

		$this->add(array(
			'name' => 'id_view',
			'type' => 'Libadmin\Form\Element\NoValidationCheckbox',
			'options' => array(
				'label' => 'This label will be changed (by the app)',
				'unchecked_value' => '0',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
			),
			'required' => false,

		));

		$this->add(array(
			'name' => 'id_group',
			'type' => 'Libadmin\Form\Element\NoValidationSelect',
			'options' => array( //				'label' => 'Gruppe'
                'label' => 'This label will be changed (by the app)',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
			)
		));

		$this->add(array(
			'name' => 'is_favorite',
			'type' => 'Libadmin\Form\Element\NoValidationCheckbox',
			'options' => array(
				'label' => 'is_favorite',
				'checked_value' => '1',
				'unchecked_value' => '0',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
			),
			'required' => false
		));
	}



	/**
	 * Modify view checkbox for relation.
	 * Set checked value to view id to pass the validation (only initial values are allowed)
	 *
	 * @param    FormInterface|InstitutionForm    $form
	 * @return mixed|void
	 */
	public function prepareElement(FormInterface $form)
	{
		parent::prepareElement($form);

		/** @var Checkbox $viewCheckbox */
		$viewCheckbox = $this->get('id_view');

		/** @var View $view */
		$view = current($form->views);
		next($form->views);

		if (!$view) {
			return;
		}

		$viewCheckbox->setCheckedValue((string)$view->getId());
		$viewCheckbox->setValue($this->object->getIdView());
		$viewCheckbox->setLabel($view->getLabel());

		/** @var Select $groupSelect */
		$groupSelect = $this->get('id_group');

		/** @var Group $group */
		$options = array();
		foreach ($form->groups as $group) {
			$options[$group->getId()] = $group->getListLabel();
		}
		$groupSelect->setValueOptions($options);
	}
}
