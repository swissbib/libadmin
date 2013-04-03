<?php
namespace Libadmin\Form;

use Libadmin\Form\BaseForm;


/**
 * [Description]
 *
 */
class InstitutionForm extends BaseForm {

	public function __construct($name = null, $options = array()) {
		parent::__construct('institution', $options);

		$this->setAttribute('method', 'post');

		$this->addHidden('id');
		$this->addText('bib_code', 'Bib-Code');
		$this->addText('label_de', 'deutsch');
		$this->addSubmit('Speichern');
	}

}

?>