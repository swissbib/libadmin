<?php
namespace Libadmin\Form;

use Libadmin\Form\BaseForm;
use Zend\Form\Element;
use Zend\Form\Fieldset;


/**
 * [Description]
 *
 */
class GroupForm extends BaseForm {

	public function __construct($name = null, $options = array()) {
		parent::__construct('group', $options);

		$this->addHidden('id');

		$this->addText('code', 'Code', true);
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
	}

}

?>






