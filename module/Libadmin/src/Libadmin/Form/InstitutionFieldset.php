<?php
namespace Libadmin\Form;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator;

use Libadmin\Form\Element\NoValidationCheckbox;
use Libadmin\Model\Institution;
use Libadmin\Model\View;
use Libadmin\Form\BaseFieldset;

/**
 * Base fieldset for institution
 * All fields are in here instead in the institution form to support relations
 *
 */
class InstitutionFieldset extends BaseFieldset implements InputFilterProviderInterface
{

	/**
	 * Initialize
	 *
	 * @param	View[]	$views
	 * @param	Array	$options
	 */
	public function __construct($views, $options = array())
	{
		parent::__construct('institution', $options);

		$this->setHydrator(new ClassMethodsHydrator(false))
				->setObject(new Institution);

		$this->addHidden('id');

		$this->addText('bib_code', 'bibcode', true);
		$this->addText('sys_code', 'syscode', true);
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

		$this->addText('name_de', 'language_german');
		$this->addText('name_fr', 'language_french');
		$this->addText('name_it', 'language_italian');
		$this->addText('name_en', 'language_english');

		$this->addText('url_de', 'language_german');
		$this->addText('url_fr', 'language_french');
		$this->addText('url_it', 'language_italian');
		$this->addText('url_en', 'language_english');

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

		$this->add(array(
			'name' => 'address',
			'type' => 'textarea',
			'options' => array(
				'label' => 'address'
			),
			'attributes' => array(
				'rows' => 6
			)
		));

		$this->addText('zip', 'zip');
		$this->addText('city', 'city');

		$this->add(array(
			'name' => 'country',
			'type' => 'select',
			'options' => array(
				'label' => 'country',
				'value_options' => array(
					'ch' => 'country_ch',
					'li' => 'country_li',
					'de' => 'country_de',
					'it' => 'country_it',
					'fr' => 'country_fr'
				)
			)
		));

		$this->add(array(
			'name' => 'canton',
			'type' => 'select',
			'options' => array(
				'label' => 'canton',
				'empty_option' => '- Kein Kanton -',
				'value_options' => array(
					'ag' => 'Aargau',
					'ai' => 'Appenzell Innerrhoden',
					'ar' => 'Appenzell Ausserrhoden',
					'be' => 'Bern',
					'bl' => 'Basel-Land',
					'bs' => 'Basel-Stadt',
					'fr' => 'Fribourg',
					'ge' => 'Genève',
					'gl' => 'Glarus',
					'gr' => 'Graubünden ',
					'ju' => 'Jura',
					'lu' => 'Luzern',
					'ne' => 'Neuchâtel',
					'nw' => 'Nidwalden',
					'ow' => 'Obwalden',
					'sg' => 'Sankt Gallen',
					'sh' => 'Schaffhausen',
					'so' => 'Solothurn',
					'sz' => 'Schwyz',
					'tg' => 'Thurgau',
					'ti' => 'Ticino',
					'ur' => 'Uri',
					'vs' => 'Valais',
					'vd' => 'Vaud',
					'zg' => 'Zug',
					'zh' => 'Zürich'
				)
			)
		));

		$this->addText('website', 'webseite');
		$this->addText('email', 'email');
		$this->addText('phone', 'telefon');
		$this->addText('skype', 'skype');
		$this->addText('facebook', 'facebook');
		$this->addText('coordinates', 'coordinates');
		$this->addText('isil', 'isil');

		$this->add(array(
				'type' => 'Zend\Form\Element\Collection',
				'name' => 'relations',
				'options' => array(
						'target_element' => array(
								'type' => 'Libadmin\Form\InstitutionRelationFieldset'
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
			'bib_code' => array(
				'required' => true,
				'filters' => array(
					array('name' => 'StringTrim')
				)
			),
			'sys_code' => array(
				'required' => true
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
			'canton' => array(
				'required' => false
			),
			'email' => array(
				'required' => false,
				'validators' => array(
					array(
						'name' => 'EmailAddress'
					)
				)
			),
			'zip' => array(
				'required' => false,
				'filters' => array(
					array('name' => 'Digits')
				)
			)
		);
	}
}
