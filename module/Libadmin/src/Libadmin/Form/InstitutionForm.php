<?php
namespace Libadmin\Form;

use Libadmin\Form\BaseForm;
use Zend\Form\Element;
use Zend\Form\Fieldset;

use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Db\ResultSet\ResultSet;


/**
 * [Description]
 *
 */
class InstitutionForm extends BaseForm {

	public $views;

	/**
	 * Initialize
	 *
	 * @param	Array		$views
	 * @param	Array		$options
	 */
	public function __construct($views = array(), $options = array()) {
		parent::__construct('institution', $options);

		$this->views	= $views;

        $this->setHydrator(new ClassMethodsHydrator(false));

        $this->add(array(
            'type'  => 'Libadmin\Form\InstitutionFieldset',
            'options'   => array(
                'use_as_base_fieldset' => true
            )
        ));
	}


	public function getViewsCount() {
		return $this->views instanceof ResultSet ? $this->views->count() : 0;
	}

}

?>






