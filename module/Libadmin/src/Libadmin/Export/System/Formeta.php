<?php
namespace Libadmin\Export\System;

use Libadmin\Services\View\FormetaModel;
use Zend\View\Model\JsonModel;
use Zend\Db\ResultSet\ResultSetInterface;

use Libadmin\Model\Group;
use Libadmin\Model\Institution;
use Zend\Config\Reader\Ini;
use Zend\Config\Config;

#use ML\JsonLD\JsonLD;

/**
 * VuFind Export system
 *
 */
class Formeta extends MapPortal
{


	/**
	 * Get vufind json data
	 *
	 * @return    JsonModel
	 */
	public function getFormetaData()
	{
		try {
			$data = array(
				'success' => true,
				'data' => $this->getJsonPayloadData()
			);
		} catch (\Exception $e) {
			$data = array(
				'success' => false,
				'data' => array(),
				'error' => $e->getMessage()
			);
		}


		return new FormetaModel($data);
	}



}


