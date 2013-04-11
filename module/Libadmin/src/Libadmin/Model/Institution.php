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
	public $sys_code;

	public $is_active;

	public $label_de;
	public $label_fr;
	public $label_it;
	public $label_en;

	public $name_de;
	public $name_fr;
	public $name_it;
	public $name_en;

	public $url_de;
	public $url_fr;
	public $url_it;
	public $url_en;

	public $address;
	public $zip;
	public $city;
	public $country;
	public $canton;

	public $website;
	public $email;
	public $phone;
	public $skype;
	public $facebook;
	public $coordinates;
	public $isil;
	public $notes;


	public $relations = array();



	/**
	 *
	 * @inheritDoc
	 * @return Array
	 */
	public function getBaseData() {
		$data = parent::getBaseData();

		unset($data['relations']);

		return $data;
	}



	/**
	 * Get list label
	 *
	 * @return	String
	 */
	public function getListLabel() {
		return $this->bib_code . ': ' . $this->label_de;
	}



	/**
	 * Get type label
	 *
	 * @return	String
	 */
	public function getTypeLabel() {
		return 'Institution';
	}



	/**
	 * @return InputFilter|InputFilterInterface
	 */
	public function getInputFilter() {
		if( !$this->inputFilter ) {
			$this->inputFilter	= new InputFilter();
			$factory     		= new InputFactory();

			$this->inputFilter->add($factory->createInput(array(
				'name'     => 'id',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			)));

			$this->inputFilter->add($factory->createInput(array(
				'name'		=> 'bib_code',
				'required'	=> true,
				'filters'	=> array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim')
				)
			)));
		}

		return $this->inputFilter;
	}



	public function setAddress($address) {
		$this->address = $address;
	}



	public function getAddress() {
		return $this->address;
	}



	public function setBib_code($bib_code) {
		$this->bib_code = $bib_code;
	}



	public function getBib_code() {
		return $this->bib_code;
	}



	public function setCanton($canton) {
		$this->canton = $canton;
	}



	public function getCanton() {
		return $this->canton;
	}



	public function setCity($city) {
		$this->city = $city;
	}



	public function getCity() {
		return $this->city;
	}



	public function setCoordinates($coordinates) {
		$this->coordinates = $coordinates;
	}



	public function getCoordinates() {
		return $this->coordinates;
	}



	public function setCountry($country) {
		$this->country = $country;
	}



	public function getCountry() {
		return $this->country;
	}



	public function setEmail($email) {
		$this->email = $email;
	}



	public function getEmail() {
		return $this->email;
	}



	public function setFacebook($facebook) {
		$this->facebook = $facebook;
	}



	public function getFacebook() {
		return $this->facebook;
	}



	public function setIs_active($is_active) {
		$this->is_active = $is_active;
	}



	public function getIs_active() {
		return $this->is_active;
	}



	public function setIsil($isil) {
		$this->isil = $isil;
	}



	public function getIsil() {
		return $this->isil;
	}



	public function setLabel_de($label_de) {
		$this->label_de = $label_de;
	}



	public function getLabel_de() {
		return $this->label_de;
	}



	public function setLabel_en($label_en) {
		$this->label_en = $label_en;
	}



	public function getLabel_en() {
		return $this->label_en;
	}



	public function setLabel_fr($label_fr) {
		$this->label_fr = $label_fr;
	}



	public function getLabel_fr() {
		return $this->label_fr;
	}



	public function setLabel_it($label_it) {
		$this->label_it = $label_it;
	}



	public function getLabel_it() {
		return $this->label_it;
	}



	public function setName_de($name_de) {
		$this->name_de = $name_de;
	}



	public function getName_de() {
		return $this->name_de;
	}



	public function setName_en($name_en) {
		$this->name_en = $name_en;
	}



	public function getName_en() {
		return $this->name_en;
	}



	public function setName_fr($name_fr) {
		$this->name_fr = $name_fr;
	}



	public function getName_fr() {
		return $this->name_fr;
	}



	public function setName_it($name_it) {
		$this->name_it = $name_it;
	}



	public function getName_it() {
		return $this->name_it;
	}



	public function setNotes($notes) {
		$this->notes = $notes;
	}



	public function getNotes() {
		return $this->notes;
	}



	public function setPhone($phone) {
		$this->phone = $phone;
	}



	public function getPhone() {
		return $this->phone;
	}



	public function setSkype($skype) {
		$this->skype = $skype;
	}



	public function getSkype() {
		return $this->skype;
	}



	public function setSys_code($sys_code) {
		$this->sys_code = $sys_code;
	}



	public function getSys_code() {
		return $this->sys_code;
	}



	public function setUrl_de($url_de) {
		$this->url_de = $url_de;
	}



	public function getUrl_de() {
		return $this->url_de;
	}



	public function setUrl_dn($url_en) {
		$this->url_en = $url_en;
	}



	public function getUrl_en() {
		return $this->url_en;
	}



	public function setUrl_fr($url_fr) {
		$this->url_fr = $url_fr;
	}



	public function getUrl_fr() {
		return $this->url_fr;
	}



	public function setUrl_it($url_it) {
		$this->url_it = $url_it;
	}



	public function getUrl_it() {
		return $this->url_it;
	}



	public function setWebsite($website) {
		$this->website = $website;
	}



	public function getWebsite() {
		return $this->website;
	}



	public function setZip($zip) {
		$this->zip = $zip;
	}



	public function getZip() {
		return $this->zip;
	}



	public function setRelations($relations) {
		$this->relations = $relations;
	}



	public function getRelations() {
		return $this->relations;
	}


}