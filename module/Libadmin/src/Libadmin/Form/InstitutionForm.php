<?php
namespace Libadmin\Form;

use Libadmin\Form\BaseForm;
use Zend\Form\Element;
use Zend\Form\Fieldset;


/**
 * [Description]
 *
 */
class InstitutionForm extends BaseForm {

	public function __construct($name = null, $options = array()) {
		parent::__construct('institution', $options);

		$this->addHidden('id');

//		$display	= new Fieldset('display');
//
//		$bibCode	= new Element\Text('bib_code', array(
//			'label' => 'Bib-Code'
//		));
//
//		$display->add($bibCode);




		$this->addText('bib_code', 'Bib-Code', true);
		$this->addText('sys_code', 'Sys-Code', true);
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

		$this->addText('name_de', 'deutsch');
		$this->addText('name_fr', 'französisch');
		$this->addText('name_it', 'italienisch');
		$this->addText('name_en', 'englisch');

		$this->addText('url_de', 'deutsch');
		$this->addText('url_fr', 'französisch');
		$this->addText('url_it', 'italienisch');
		$this->addText('url_en', 'englisch');

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

		$this->add(array(
			'name' => 'address',
			'type'  => 'textarea',
			'options' => array(
				'label' => 'Adresse'
			),
			'attributes' => array(
				'rows'	=> 6
			)
		));

		$this->addText('zip', 'PLZ');
		$this->addText('city', 'Ort');

		$this->add(array(
			'name' => 'country',
			'type'  => 'select',
			'options' => array(
				'label' => 'Land',
				'value_options' => array(
					 'ch' => 'Schweiz',
					 'li' => 'Lichtenstein',
					 'de' => 'Deutschland',
					 'it' => 'Italien',
					 'fr' => 'Frankreich'
				 )
			)
		));

		$this->add(array(
			'name' => 'canton',
			'type'  => 'select',
			'options' => array(
				'label' => 'Kanton',
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

		$this->addText('website', 'Webseite');
		$this->addText('email', 'Email');
		$this->addText('phone', 'Telefon');
		$this->addText('skype', 'Skype');
		$this->addText('facebook', 'Facebook');
		$this->addText('coordinates', 'Koordinaten');
		$this->addText('isil', 'ISIL');
	}

}

?>






