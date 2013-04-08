<?php
namespace Libadmin\Form;

use Libadmin\Form\BaseForm;
use Zend\Form\Element;
use Zend\Form\Fieldset;


/**
 * [Description]
 *
 */
class ViewForm extends BaseForm {

	/**
	 * Initialize
	 *
	 * @param	String|Null		$name
	 * @param	Array			$options
	 */
	public function __construct($name = null, $options = array()) {
		parent::__construct('group', $options);

		$this->addHidden('id');

		$this->addText('code', 'Code', true);
		$this->addText('label', 'Name', true);

		$this->add(array(
			'name' => 'is_active',
			'type'  => 'checkbox',
			'options' => array(
				'label' => 'Ist Aktiv'
			)
		));

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






