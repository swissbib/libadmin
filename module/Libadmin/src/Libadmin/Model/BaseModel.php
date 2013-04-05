<?php
namespace Libadmin\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

/**
 * [Description]
 *
 */
abstract class BaseModel implements InputFilterAwareInterface {

	public $id;

	/**
	 * @var InputFilter
	 */
	protected $inputFilter;



	/**
	 * Set input filter
	 *
	 * @param    InputFilterInterface $inputFilter
	 * @return    InputFilterAwareInterface
	 * @throws    \Exception
	 */
	public function setInputFilter(InputFilterInterface $inputFilter) {
		throw new \Exception("Not used");
	}



	/**
	 * Retrieve input filter
	 *
	 * @return InputFilterInterface
	 */
	public function getInputFilter() {
		if( !$this->inputFilter ) {
			$this->inputFilter = new InputFilter();
		}

		return $this->inputFilter;
	}



	public function getArrayCopy() {
		return get_object_vars($this);
	}


	public function getData() {
		$data	= $this->getArrayCopy();

		unset($data['id']);
		unset($data['inputFilter']);

		return $data;
	}



	public function exchangeArray($data) {
		if( is_object($data) ) {
			$data = $data->getArrayCopy();
		}

		$this->initLocalVariables($data);
	}



	/**
	 * Get record ID
	 *
	 * @return	Integer
	 */
	public function getID() {
		return (int)$this->id;
	}

	protected function initLocalVariables(array $data) {
		foreach($data as $key => $value) {
			if( property_exists($this, $key) ) {
				$this->$key = $value;
			}
		}
	}


	abstract public function getListLabel();

	abstract public function getTypeLabel();

}

?>