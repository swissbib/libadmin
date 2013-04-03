<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/13/12
 * Time: 2:59 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Libadmin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

use Libadmin\Model\BaseModel;



class Institution extends BaseModel {

	public $id;

	public $bib_code;

	public $label_de;



	/**
	 * @return InputFilter|InputFilterInterface
	 */
	public function getInputFilter() {
		if( !$this->inputFilter ) {
			$inputFilter = new InputFilter();
			$factory     = new InputFactory();

			$inputFilter->add($factory->createInput(array(
				'name'     => 'id',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			)));
		}

		return $this->inputFilter;
	}


//	public function getInputFilter() {
//		if( !$this->inputFilter ) {
//			$inputFilter = new InputFilter();
//			$factory = new InputFactory();
//
//			$inputFilter->add($factory->createInput(array(
//				'name' => 'tablekey',
//				'required' => true,
//				'filters' => array(
//					array('name' => 'Int'),
//				),
//			)));
//
//			$inputFilter->add($factory->createInput(array(
//				'name' => 'libraryid',
//				'required' => true,
//				'filters' => array(
//					array('name' => 'StripTags'),
//					array('name' => 'StringTrim'),
//				),
//				'validators' => array(
//					array(
//						'name' => 'StringLength',
//						'options' => array(
//							'encoding' => 'UTF-8',
//							'min' => 1,
//							'max' => 25,
//						),
//					),
//				),
//			)));
//
//			$inputFilter->add($factory->createInput(array(
//				'name' => 'contentXML',
//				'required' => true,
//				'filters' => array(
//					array('name' => 'StringTrim'),
//				),
//			)));
//
//			$this->inputFilter = $inputFilter;
//		}
//
//		return $this->inputFilter;
//	}




//	public function parseContentXML() {
//
//		try {
//			$sxml = new \SimpleXMLElement($this->contentXML);
//		} catch( \Exception $e ) {
//			echo $e->getMessage();
//		}
//
//		$libraryItem = array();
//		foreach($sxml->children() as $attr => $val) {
//
//			//echo $val;
//			switch( $attr ) {
//				case "adress":
//					$adress = array();
//					foreach($val->children() as $adressPart => $val) {
//						$adress[$adressPart] = (string)$val;
//					}
//
//					$libraryItem["adress"] = $adress;
//
//					break;
//				case "translations":
//					$translations = array();
//					foreach($val->children() as $langName => $val) {
//						$t = (array)$val->attributes();
//						$lang_code = $t["@attributes"]["lang"];
//						$translations[$lang_code] = (string)$val;
//					}
//
//					$libraryItem["translations"] = $translations;
//
//					break;
//				default:
//					$libraryItem[$attr] = (string)$val;
//			}
//
//		}
//
//		$this->libraryElements = $libraryItem;
//
//	}


//
//	public function getLibraryElements() {
//		return $this->libraryElements;
//	}

}

//
//class LibraryAdress {
//
//	public $road;
//
//	public $town;
//
//	public $zipcode;
//}
//
//class Translations {
//
//	public $name_de;
//
//	public $name_fr;
//
//	public $name_it;
//
//	public $name_rr;
//}
