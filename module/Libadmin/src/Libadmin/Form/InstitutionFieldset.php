<?php
namespace Libadmin\Form;

use Zend\Form\Fieldset;
use Zend\Form\FormInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Libadmin\Model\Institution;

/**
 * Base fieldset for institution
 * All fields are in here instead in the institution form to support relations
 *
 */
class InstitutionFieldset extends Fieldset {

	/**
	 * Initialize
	 *
	 */
	public function __construct() {
        parent::__construct('institution');

        $this->setHydrator(new ClassMethodsHydrator(false))
               ->setObject(new Institution());

        $this->addHidden('id');

        $this->addText('bib_code', 'bibcode', true);
        $this->addText('sys_code', 'syscode', true);
        $this->add(array(
            'name' => 'is_active',
            'type'  => 'checkbox',
            'options' => array(
                'label' => 'is_active'
            )
        ));
        $this->addText('label_de', 'language_german', true);
        $this->addText('label_fr', 'language_french');
        $this->addText('label_it', 'language_italian');
        $this->addText('label_en', 'language_english');

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
            'type'  => 'textarea',
            'options' => array(
                'label' => 'internal_notes'
            ),
            'attributes' => array(
                'rows'	=> 10
            )
        ));

        $this->add(array(
            'name' => 'address',
            'type'  => 'textarea',
            'options' => array(
                'label' => 'address'
            ),
            'attributes' => array(
                'rows'	=> 6
            )
        ));

        $this->addText('zip', 'zip');
        $this->addText('city', 'city');

        $this->add(array(
            'name' => 'country',
            'type'  => 'select',
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
            'type'  => 'select',
            'options' => array(
                'label' => 'canton',
                'value_options' => array(
                    '0'		=> '- Kein Kanton -',
                    'ag'	=> 'Aargau',
                    'ai'	=> 'Appenzell Innerrhoden',
                    'ar'	=> 'Appenzell Ausserrhoden',
                    'be'	=> 'Bern',
                    'bl'	=> 'Basel-Land',
                    'bs'	=> 'Basel-Stadt',
                    'fr'	=> 'Fribourg',
                    'ge'	=> 'Genève',
                    'gl'	=> 'Glarus',
                    'gr'	=> 'Graubünden ',
                    'ju'	=> 'Jura',
                    'lu'	=> 'Luzern',
                    'ne'	=> 'Neuchâtel',
                    'nw'	=> 'Nidwalden',
                    'ow'	=> 'Obwalden',
                    'sg'	=> 'Sankt Gallen',
                    'sh'	=> 'Schaffhausen',
                    'so'	=> 'Solothurn',
                    'sz'	=> 'Schwyz',
                    'tg'	=> 'Thurgau',
                    'ti'	=> 'Ticino',
                    'ur'	=> 'Uri',
                    'vs'	=> 'Valais',
                    'vd'	=> 'Vaud',
                    'zg'	=> 'Zug',
                    'zh'	=> 'Zürich'
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

			// Relation fieldset (this may be replaced for new records in prepareElement() method)
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
	 * Prepare elements
	 * Replace relations with the correct amount of items
	 *
	 * @param	FormInterface	$form
	 * @return	void
	 */
	public function prepareElement(FormInterface $form) {
		$viewCount	= $form->getViewsCount();
		$relations	= $this->get('relations');

		if( $viewCount !== $relations->getCount() ) {
			/** @var InstitutionForm $form */
			$this->remove('relations');

			$this->add(array(
				'type' => 'Zend\Form\Element\Collection',
				'name' => 'relations',
				'options' => array(
					'count' => $form->getViewsCount(),
					'target_element' => array(
						'type' => 'Libadmin\Form\InstitutionRelationFieldset'
					)
				)
			));
		}

		parent::prepareElement($form);
	}



	/**
   	 * Add hidden field
   	 *
   	 * @param	String		$name
   	 */
   	protected function addHidden($name) {
   		$this->add(array(
   			'name' => $name,
   			'attributes' => array(
   				'type'  => 'hidden',
   			),
   		));
   	}



   	/**
   	 * Add text field
   	 *
   	 * @param	String		$name
   	 * @param	String		$label
   	 * @param	Boolean		$required
   	 */
   	protected function addText($name, $label, $required = false) {
   		$this->add(array(
   			'name' => $name,
   			'attributes' => array(
   				'type'  => 'text',
   				'required'	=> !!$required
   			),
   			'options' => array(
   				'label' => $label
   			),
   		));
   	}

}

?>