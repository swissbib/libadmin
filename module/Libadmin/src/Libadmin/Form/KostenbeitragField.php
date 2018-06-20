<?php
namespace Libadmin\Form;

use Libadmin\Model\Kostenbeitrag;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator;

use Libadmin\Form\Element\NoValidationCheckbox;
use Libadmin\Model\Institution;
use Libadmin\Model\View;
use Libadmin\Form\BaseFieldset;

use Zend\Hydrator\ObjectProperty as ObjectPropertyHydrator;


/**
 * Base fieldset for Kostenbeitrag
 *
 */
class KostenbeitragFieldset extends BaseFieldset implements InputFilterProviderInterface
{

    public function __construct()
    {
        parent::__construct('kostenbeitrag');
    }

    public function init()
	{


        $this->setName('kostenbeitrag');

		$this->setHydrator(new ClassMethodsHydrator(false))
				->setObject(new Kostenbeitrag());

		$this->addHidden('id');

        $this->addText('j2018', 'j2018');
        $this->addText('j2019', 'j2019');
        $this->addText('j2020', 'j2020');
	}



	/**
	 * Get input filters and validations
	 *
	 * @return    array
	 */
	public function getInputFilterSpecification()
	{
		return [
			'name' => [
				'required' => false
            ]
        ];
	}
}
