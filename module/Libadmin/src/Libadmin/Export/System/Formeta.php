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
     * @var Config
     */
    protected $configLinkedRepositories;

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

    /**
     * Get grouped institution data
     *
     * @return    Array
     */
    protected function getJsonPayloadData()
    {
        $data = array();
        $groups = $this->getGroups();
        //$extractInstitutionMethod = $this->getOption('all') == true ? 'extractAllInstitutionData' : 'extractInstitutionData';
        //the whole information should always be delivered to the client
        $extractInstitutionMethod = 'extractAllInstitutionData';


        foreach ($groups as $group) {

            $repositoryForExportDefined = $this->configLinkedRepositories->LINKED_DATA_REPOSITORIES->offsetGet($group->code);
            if (isset($repositoryForExportDefined) && $repositoryForExportDefined ) {
                $groupData = array(
                    'group' => $this->extractGroupData($group),
                    'institutions' => array()
                );

                $institutions = $this->getGroupInstitutions($group);

                foreach ($institutions as $institution) {
                    $groupData['institutions'][] = $this->$extractInstitutionMethod($institution,$group->code);
                }

                $data[] = $groupData;

            }


        }

        return $data;
    }

    /**
     * @override
     * @return void
     */
    public function init()
    {

        //http://localhost/libadmin/api/semanticweb/green.json?option[all]=true
        //http://www.cambridgesemantics.com/semantic-university/getting-started
        //documentation
        //http://code.ohloh.net/project?pid=jZRKcGNwZOo&cid=9cCNLmy7F0s&fp=291221&mp=&projSelected=true
        parent::init();

        $config =  $this->getServiceLocator()->get('config');
        $reader = new Ini();
        $this->configLinkedRepositories   =  new Config($reader->fromFile($config['libadmin']['linkedswissbibconfig']));

    }


}


