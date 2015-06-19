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

    /**
     * Extract required data from institution
     *
     * @param    Institution        $institution
     * @return    Array
     */
    protected function extractAllInstitutionData(Institution $institution, $groupCode)
    {
        $test = "";

        return ['institution' => [

            'id'    => $institution->getBib_code(),
            'sysDbId'		=> $institution->getId(),
            'sys_code'	=> $institution->getSys_code(),
            'label' => array(
                'de' => $institution->getLabel_de(),
                'fr' => $institution->getLabel_fr(),
                'it' => $institution->getLabel_it(),
                'en' => $institution->getLabel_en()
            ),
            'name' => array(
                'de' => $institution->getName_de(),
                'fr' => $institution->getName_fr(),
                'it' => $institution->getName_it(),
                'en' => $institution->getName_en()
            ),
            'url' => array(
                'de' => $institution->getUrl_de(),
                'fr' => $institution->getUrl_fr(),
                'it' => $institution->getUrl_it(),
                'en' => $institution->getUrl_en()
            ),
            'address'		=> $institution->getAddress(),
            'zip'			=> $institution->getZip(),
            'city'			=> $institution->getCity(),
            'country'		=> $institution->getCountry(),
            'canton'		=> $institution->getCanton(),
            'website'		=> $institution->getWebsite(),
            'email'			=> $institution->getEmail(),
            'phone'			=> $institution->getPhone(),
            'skype'			=> $institution->getSkype(),
            'facebook'		=> $institution->getFacebook(),
            'coordinates'	=> $institution->getCoordinates(),
            'isil'			=> $institution->getIsil(),
            'notes'			=> $institution->getNotes(),
            'backlink'      => $this->getBackLink($groupCode,$institution->getBib_code(),array())

        ]];

    }


}


