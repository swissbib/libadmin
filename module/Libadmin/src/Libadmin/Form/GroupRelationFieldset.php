<?php
namespace Libadmin\Form;

use Libadmin\Form\Element\InstitutionList;
use Libadmin\Model\Institution;
use Libadmin\Model\InstitutionRelation;
use Libadmin\Model\View;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Libadmin\Model\InstitutionRelationList;

/**
 * [Description]
 *
 */
class GroupRelationFieldset extends BaseFieldset implements InputFilterProviderInterface
{
	public function __construct($name = '', array $options = array())
	{
		parent::__construct('relations', $options);

		$this->setHydrator(new ArraySerializable);
		$this->setObject(new InstitutionRelationList());

		$this->add(array(
					'type'	=> 'Libadmin\Form\Element\InstitutionList',
					'name'	=> 'source'
				   ));
		$this->add(array(
					'type'	=> 'Libadmin\Form\Element\InstitutionList',
					'name'	=> 'institutions'
				   ));

		$this->add(array(
					'type'	=> 'button',
					'name'	=> 'add',
					'options'	=> array(
						'label'	=> 'Add >'
					),
					'attributes'	=> array(
						'value'	=> 'add'
					)
			   ));
		$this->add(array(
					'type'	=> 'button',
					'name'	=> 'remove',
					'options'	=> array(
						'label'	=> '< Remove'
					),
					'attributes'	=> array(
						'value'	=> 'remove'
					)
			   ));

		$this->add(array(
					'type'	=> 'hidden',
					'name'	=> 'view',
					'value'	=> 0
				   ));
	}



	/**
	 * @param	GroupForm|FormInterface		$form
	 * @return	void
	 */
	public function prepareElement(FormInterface $form)
	{
		parent::prepareElement($form);

		$view	= $this->getMatchingView($form);

			// Set view label
		$this->setLabel($view->getLabel());

			// Set hidden view parameter
		$this->get('view')->setValue($view->getId());

		$this->fillSelectionList($form);
		$this->fillSourceList($form->getInstitutions());
	}



	public function populateValues($data)
	{
		if (!isset($data['institutions'])) {
			$data = array(
						'institutions' => $data
					);
		}

		parent::populateValues($data);
	}



	/**
	 * @param	GroupForm	$form
	 */
	protected function fillSelectionList(GroupForm $form)
	{
		/** @var InstitutionList $institutionSelect */
		$institutionSelect	= $this->get('institutions');
		$options			= array();

		foreach ($this->object->getRelations() as $institutionRelation) {
			/** @var InstitutionRelation $institutionRelation */
			$options[$institutionRelation->getIdInstitution()] = $form->getInstitution($institutionRelation->getIdInstitution())->getListLabel(); // 'Institution id: ' . $institutionRelation->getIdInstitution();
		}

		$institutionSelect->setValueOptions($options);
	}



	/**
	 * @param	Institution[]	$institutions
	 */
	protected function fillSourceList(array $institutions)
	{
		/** @var InstitutionList $sourceSelect */
		$sourceSelect	= $this->get('source');
		$options		= array();

		foreach ($institutions as $institution) {
			$options[$institution->getId()] = $institution->getListLabel();
		}

		$sourceSelect->setValueOptions($options);
	}



	/**
	 *
	 *
	 * @param	GroupForm	$form
	 * @return	View
	 */
	protected function getMatchingView($form)
	{
		preg_match('/\]\[(\d+)\]/', $this->attributes['name'], $matches);

		$pos	= intval($matches[1]);

		return $form->getView($pos);
	}



	/**
	 * Get input filters and validations
	 *
	 * @return    Array
	 */
	public function getInputFilterSpecification()
	{
		return array();
	}
}
