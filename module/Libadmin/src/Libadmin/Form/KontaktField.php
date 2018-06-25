<?php
namespace Libadmin\Form;

use Libadmin\Model\Kontakt;
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
 * Base fieldset for Kontakt
 *
 */
class KontaktFieldset extends BaseFieldset implements InputFilterProviderInterface
{

    public function __construct()
    {
        parent::__construct('kontakt');
    }

    public function init()
	{


        $this->setName('kontakt');

		$this->setHydrator(new ClassMethodsHydrator(false))
				->setObject(new Kontakt());

		$this->addHidden('id');

        $this->addText('name', 'name');
        $this->addText('vorname', 'vorname');
        $this->addText('anrede', 'anrede');
        $this->addText('email', 'email');

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
            ],
            'email' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => 'EmailAddress'
                    ]
                ]
            ],
        ];
	}
}
