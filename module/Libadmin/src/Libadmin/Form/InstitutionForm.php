<?php
namespace Libadmin\Form;

use Zend\Form\Element;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Db\ResultSet\ResultSet;

use Libadmin\Form\BaseForm;
use Libadmin\Model\View;


/**
 * Form for institution
 * All fields are located in the institution fieldset
 * Initialized with the list of views for internal use
 *
 */
class InstitutionForm extends BaseForm {

	/**
	 * @var	ResultSet	Contains views
	 */
	public $views;



	/**
	 * Initialize
	 *
	 * @param	ResultSet		$views
	 * @param	Array			$options
	 */
	public function __construct(ResultSet $views = null, $options = array()) {
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



	/**
	 * Get amount of views
	 *
	 * @return	Integer
	 */
	public function getViewsCount() {
		return $this->views instanceof ResultSet ? $this->views->count() : 0;
	}

}

?>






