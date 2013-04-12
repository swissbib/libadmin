<?php
namespace Libadmin\Export\System;

use Zend\View\Model\JsonModel;

/**
 * VuFind Export system
 *
 */
class Vufind extends System {

	/**
	 * Get vufind json data
	 *
	 * @return	JsonModel
	 */
	public function getJsonData() {
		$result = new JsonModel(array(
			'info'	=> 'Output für view ' . $this->getView() . ' in format json with options ' . serialize($this->getOptions()),
			'hallo'	=> 'welt'
		));

		return$result;
	}

}