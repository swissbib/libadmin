<?php
namespace Libadmin\Form;

use Libadmin\Model\Adresse;
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
class AdresseFieldset extends BaseFieldset implements InputFilterProviderInterface
{

    public function __construct()
    {
        parent::__construct('adresse');
    }

    public function init()
	{


        $this->setName('adresse');

		$this->setHydrator(new ClassMethodsHydrator(false))
				->setObject(new Adresse());

		$this->addHidden('id');

        $this->addText('strasse', 'strasse');
        $this->addText('nummer', 'nummer');
        $this->addText('zusatz', 'zusatz');
        $this->addText('plz', 'plz');
        $this->addText('ort', 'ort');


        $this->addSelect(
            'country',
            'country',
            [
                'ch' => 'country_ch',
                '' => '-',
                'li' => 'country_li',
                'de' => 'country_de',
                'it' => 'country_it',
                'fr' => 'country_fr'
            ]
        );

        $this->addSelect(
            'canton',
            'canton',
            [
                ''   => '- Kein Kanton -',
                'ag' => 'Aargau',
                'ai' => 'Appenzell Innerrhoden',
                'ar' => 'Appenzell Ausserrhoden',
                'be' => 'Bern',
                'bl' => 'Basel-Land',
                'bs' => 'Basel-Stadt',
                'fr' => 'Fribourg',
                'ge' => 'Genève',
                'gl' => 'Glarus',
                'gr' => 'Graubünden ',
                'ju' => 'Jura',
                'lu' => 'Luzern',
                'ne' => 'Neuchâtel',
                'nw' => 'Nidwalden',
                'ow' => 'Obwalden',
                'sg' => 'Sankt Gallen',
                'sh' => 'Schaffhausen',
                'so' => 'Solothurn',
                'sz' => 'Schwyz',
                'tg' => 'Thurgau',
                'ti' => 'Ticino',
                'ur' => 'Uri',
                'vs' => 'Valais',
                'vd' => 'Vaud',
                'zg' => 'Zug',
                'zh' => 'Zürich'
            ]
        );

        $this->addText('name_organisation', 'name_organisation');
	}



	/**
	 * Get input filters and validations
	 *
	 * @return    array
	 */
	public function getInputFilterSpecification()
	{
		return [
            'canton' => [
                'required' => false
            ],
            'country' => [
                'required' => false
            ],
        ];
	}
}
