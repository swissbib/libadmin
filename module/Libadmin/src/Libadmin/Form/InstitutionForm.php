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
			),
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


	}

}

?>