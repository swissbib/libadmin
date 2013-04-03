<?php
namespace Libadmin\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

/**
 * [Description]
 *
 */
class BaseModel implements InputFilterAwareInterface {

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



	public function exchangeArray($data) {
		$this->initLocalVariables($data);
	}

	protected function initLocalVariables(array $data) {
		foreach($data as $key => $value) {
			if( isset($this->$key) ) {
				$this->$key = $value;
			}
		}
	}

}

?>