<?php
namespace Libadmin\Form;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator;

use Libadmin\Form\Element\NoValidationCheckbox;
use Libadmin\Model\Institution;
use Libadmin\Model\View;
use Libadmin\Form\BaseFieldset;

/**
 * Base fieldset for institution
 * All fields are in here instead in the institution form to support relations
 *
 */
class InstitutionFieldset extends BaseFieldset implements InputFilterProviderInterface
{

	public function init()
	{


        $this->setName('institution');

		$this->setHydrator(new ClassMethodsHydrator(false))
				->setObject(new Institution);

		$this->addHidden('id');

		$this->addText('bib_code', 'bibcode', true);
		$this->addText('sys_code', 'syscode', true);
		$this->addCheckbox('is_active', 'is_active');

		$this->addText('label_de', 'language_german', true);
		$this->addText('label_fr', 'language_french', true);
		$this->addText('label_it', 'language_italian', true);
		$this->addText('label_en', 'language_english', true);

		$this->addText('name_de', 'language_german');
		$this->addText('name_fr', 'language_french');
		$this->addText('name_it', 'language_italian');
		$this->addText('name_en', 'language_english');

		$this->addText('url_de', 'language_german');
		$this->addText('url_fr', 'language_french');
		$this->addText('url_it', 'language_italian');
		$this->addText('url_en', 'language_english');

        $this->addText('twitter', 'twitter');
        $this->addText('url_web_de', 'url_web_de');
        $this->addText('url_web_en', 'url_web_en');
        $this->addText('url_web_it', 'url_web_it');
        $this->addText('url_web_fr', 'url_web_fr');
        $this->addText('adresszusatz', 'adresszusatz');
        $this->addText('id_kontakt', 'id_kontakt');
        $this->addText('korrespondenzsprache', 'korrespondenzsprache');

        $this->addSelect(
            'korrespondenzsprache',
            'korrespondenzsprache',
            [
                ''   => '-',
                'g' => 'german',
                'f' => 'french',
                'i' => 'italian',
            ]
        );

        $this->addText('bfscode', 'bfscode');


        $this->addText('worldcat_symbol', 'worldcat_symbol');
        $this->addCheckbox('worldcat_ja_nein', 'worldcat_ja_nein', 'ja', 'nein');
        $this->addText('cbslibrarycode', 'cbslibrarycode');
        $this->addText('verrechnungbeitrag', 'verrechnungbeitrag');
        $this->addSelect(
            'zusage_beitrag',
            'zusage_beitrag',
            [
                ''   => '-',
                'ja' => 'yes',
                'nein' => 'no',
                'offen' => 'open',
            ]
        );
        $this->addText('id_kostenbeitrag', 'id_kostenbeitrag');

        $this->add([
            'type' => 'Libadmin\Form\KostenbeitragFieldset',
            'name' => 'kostenbeitrag',
        ]);

        $this->add([
            'name' => 'bemerkung_kostenbeitrag',
            'type' => 'textarea',
            'options' => [
                'label' => 'bemerkung_kostenbeitrag'
            ],
            'attributes' => [
                'rows' => 3
            ]
        ]);

        $this->addCheckbox('adresse_rechnung_gleich_post', 'adresse_rechnung_gleich_post', 'ja', 'nein');
        $this->addText('id_rechnungsadresse', 'id_rechnungsadresse');
        $this->addText('id_kontakt_rechnung', 'id_kontakt_rechnung');
        $this->addCheckbox('mwst', 'mwst', 'ja', 'nein');

        $this->addSelect(
            'grund_mwst_frei',
            'grund_mwst_frei',
            [
                ''   => '-',
                'bfk' => 'bfk_regelung',
                'gemeinwesen' => 'eigenes_gemeinwesen',
            ]
        );

        $this->addCheckbox('e_rechnung', 'e_rechnung', 'ja', 'nein');
        $this->addText('bemerkung_rechnung', 'bemerkung_rechnung');

        $this->addText('kostenbeitrag_basiert_auf', 'kostenbeitrag_basiert_auf');
        $this->addSelect(
            'kostenbeitrag_basiert_auf',
            'kostenbeitrag_basiert_auf',
            [
                ''   => '-',
                'bfs_zahlen' => 'bfs_zahlen',
                'anzahl_aufnahmen' => 'anzahl_aufnahmen',
                'freiwilliger_beitrag' => 'freiwilliger_beitrag',
                'recherchierte_bfs_zahlen' => 'recherchierte_bfs_zahlen',
            ]
        );

        $this->add([
            'type' => 'Libadmin\Form\KontaktFieldset',
            'name' => 'kontakt_rechnung',
        ]);
        



        $this->add([
			'name' => 'notes',
			'type' => 'textarea',
			'options' => [
				'label' => 'internal_notes'
            ],
			'attributes' => [
				'rows' => 10
            ]
        ]);

        $this->add([
            'name' => 'notes_public_de',
            'type' => 'textarea',
            'options' => [
                'label' => 'notes_public_de'
            ],
            'attributes' => [
                'rows' => 3
            ]
        ]);

        $this->add([
            'name' => 'notes_public_fr',
            'type' => 'textarea',
            'options' => [
                'label' => 'notes_public_fr'
            ],
            'attributes' => [
                'rows' => 3
            ]
        ]);

        $this->add([
            'name' => 'notes_public_it',
            'type' => 'textarea',
            'options' => [
                'label' => 'notes_public_it'
            ],
            'attributes' => [
                'rows' => 3
            ]
        ]);

        $this->add([
            'name' => 'notes_public_en',
            'type' => 'textarea',
            'options' => [
                'label' => 'notes_public_en'
            ],
            'attributes' => [
                'rows' => 3
            ]
        ]);



        $this->add([
            'type' => 'Libadmin\Form\AdresseFieldset',
            'name' => 'postadresse',
        ]);


		$this->addText('website', 'webseite');
		$this->addText('email', 'email');
		$this->addText('phone', 'telefon');
		$this->addText('skype', 'skype');
		$this->addText('facebook', 'facebook');
		$this->addText('coordinates', 'coordinates');
		$this->addText('isil', 'isil');


        $this->add([
            'type' => 'Libadmin\Form\KontaktFieldset',
            'name' => 'kontakt',
        ]);

        $this->add([
            'type' => 'Libadmin\Form\AdresseFieldset',
            'name' => 'rechnungsadresse',
        ]);



		$this->add([
				'type' => 'Zend\Form\Element\Collection',
				'name' => 'relations',
				'options' => [
						'target_element' => [
								'type' => 'Libadmin\Form\InstitutionRelationFieldset'
                        ]
                ]
        ]);
	}



	/**
	 * Get input filters and validations
	 *
	 * @return    array
	 */
	public function getInputFilterSpecification()
	{
		return [
			'bib_code' => [
				'required' => true,
				'filters' => [
					['name' => 'StringTrim']
                ]
            ],
			'sys_code' => [
				'required' => true
            ],
			'label_de' => [
				'required' => true
            ],
			'label_fr' => [
				'required' => true
            ],
			'label_it' => [
				'required' => true
            ],
			'label_en' => [
				'required' => true
            ],
			'canton' => [
				'required' => false
            ],
            'zusage_beitrag' => [
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
			'zip' => [
				'required' => false,
				'filters' => [
					['name' => 'Digits']
                ]
            ],
            'grund_mwst_frei' => [
                'required' => false
            ],
        ];
	}
}
