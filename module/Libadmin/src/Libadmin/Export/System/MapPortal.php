<?php
namespace Libadmin\Export\System;

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
class MapPortal extends System
{

	/** @var InstitutionRelationTable */
	protected $institutionRelationTable;
    protected $configHoldings;
    protected $networks;

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
		$this->institutionRelationTable = $this->getServiceLocator()->get('Libadmin\Table\InstitutionRelationTable');
        $config = $this->institutionRelationTable = $this->getServiceLocator()->get('config');
        $reader = new Ini();
        $this->configHoldings   =  new Config($reader->fromFile($config['libadmin']['backlinksconfig']));


        $networkNames = array('Aleph', 'Virtua');

        foreach ($networkNames as $networkName) {
            $configName = ucfirst($networkName) . 'Networks';

           //@var Config $networkConfigs
            $networkConfigs = $this->configHoldings->get($configName);

            foreach ($networkConfigs as $networkCode => $networkConfig) {
                list($domain, $library) = explode(',', $networkConfig, 2);

                $this->networks[$networkCode] = array(
                    'domain' => $domain,
                    'library' => $library,
                    'type' => $networkName
                );
            }
        }




	}



	/**
	 * Get vufind json data
	 *
	 * @return    JsonModel
	 */
	public function getJsonData()
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


		return new JsonModel($data);
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
		$extractInstitutionMethod = $this->getOption('all') == true ? 'extractAllInstitutionData' : 'extractInstitutionData';

		foreach ($groups as $group) {

            $repositoryForExportDefined = $this->configHoldings->MAP_PORTAL_REPOSITORIES->offsetGet($group->code);
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
	 * Extract required data from group
	 *
	 * @param    Group    $group
	 * @return    Array
	 */
	protected function extractGroupData(Group $group)
	{
		return array(
			'id' => $group->getId(),
			'code' => $group->getCode(),
			'label' => array(
				'de' => $group->getLabel_de(),
				'fr' => $group->getLabel_fr(),
				'it' => $group->getLabel_it(),
				'en' => $group->getLabel_en()
			)
		);
	}



	/**
	 * Extract required data from institution
	 *
	 * @param    Institution        $institution
	 * @return    Array
	 */
	protected function extractInstitutionData(Institution $institution, $groupCode)
	{

		return array(
			'id' 		=> $institution->getId(),
			'bib_code' 	=> $institution->getBib_code(),
			'sys_code' 	=> $institution->getSys_code(),
			//'position' 	=> $institution->getPosition(),
			//'favorite'	=> $institution->isFavorite(),
			'address' => array(
				'address'	=> $institution->getAddress(),
				'zip'		=> $institution->getZip(),
				'city'		=> $institution->getCity()
			),
			'label' => array(
				'de' => $institution->getLabel_de(),
				'fr' => $institution->getLabel_fr(),
				'it' => $institution->getLabel_it(),
				'en' => $institution->getLabel_en()
			),
//			'name' => array(
//				'de' => $institution->getName_de(),
//				'fr' => $institution->getName_fr(),
//				'it' => $institution->getName_it(),
//				'en' => $institution->getName_en()
//			),
			'url' => array(
				'de' => $institution->getUrl_de(),
				'fr' => $institution->getUrl_fr(),
				'it' => $institution->getUrl_it(),
				'en' => $institution->getUrl_en()
			),
            'backlink' => $this->getBackLink($groupCode,$institution->getBib_code(),array())

		);
	}



	/**
	 * Extract required data from institution
	 *
	 * @param    Institution        $institution
	 * @return    Array
	 */
	protected function extractAllInstitutionData(Institution $institution, $groupCode)
	{
		//$relations = $this->institutionRelationTable->getInstitutionRelations($institution->getId());
		//$institution->setRelations($relations);

		return array(
			'id'		=> $institution->getId(),
			'bib_code'	=> $institution->getBib_code(),
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
		);
	}



	/**
	 * Get groups for view
	 *
	 * @return Group[]|ResultSetInterface|null
	 */
	protected function getGroups()
	{
		$idView = $this->getView()->getId();

//		return $this->groupTable->getViewGroupsRelatedViaInstitution($idView, true);
		return $this->groupTable->getViewGroups($idView);
	}



	/**
	 * Get institutions for group in view
	 *
	 * @param    Group    $group
	 * @return    Institution[]|ResultSetInterface|null
	 */
	protected function getGroupInstitutions(Group $group)
	{
		$idView = $this->getView()->getId();
		$idGroup = $group->getId();

		return $this->institutionTable->getAllGroupViewInstitutions($idView, $idGroup);
	}


    protected function getBackLink($networkCode, $institutionCode, array $item)
    {
        //item has url (subfield u)
        if (!empty($item['holding_url'])) return $item['holding_url'];

        $method = false;
        $data = array();

        if (isset($this->configHoldings->Backlink->{$networkCode})) { // Has the network its own backlink type
            $method = 'getBackLink' . ucfirst($networkCode);
            $data = array(
                'pattern' => $this->configHoldings->Backlink->{$networkCode}
            );
        } else { // no custom type
            if (isset($this->networks[$networkCode])) { // is network even configured?
                $networkType = strtoupper($this->networks[$networkCode]['type']);
                $method = 'getBackLink' . ucfirst($networkType);

                // Has the network type (aleph, virtua, etc) a general link?
                if (isset($this->configHoldings->Backlink->$networkType)) {
                    $data = array(
                        'pattern' => $this->configHoldings->Backlink->$networkType
                    );
                }
            }
        }

        // Merge in network data if available
        if (isset($this->networks[$networkCode])) {
            $data = array_merge($this->networks[$networkCode], $data);
        }

        // Is a matching method available?
        if ($method && method_exists($this, $method)) {
            return $this->{$method}($networkCode, $institutionCode, $item, $data);
        }

        return "";
    }


    /**
     * Get back link for aleph
     *
     * @param    String $networkCode
     * @param    String $institutionCode
     * @param    Array $item
     * @param    Array $data
     * @return    String
     */
    protected function getBackLinkAleph($networkCode, $institutionCode, $item, array $data)
    {
        $values = array(
            'server' => $data['domain'],
            'bib-library-code' => $data['library'],
            //'bib-system-number' => $item['bibsysnumber'],
            'aleph-sublibrary-code' => $institutionCode
        );

        return $this->compileString($data['pattern'], $values);
    }


    /**
     * Get back link for virtua
     *
     * @todo    Get user language
     * @param    String $networkCode
     * @param    String $institutionCode
     * @param    Array $item
     * @param    Array $data
     * @return    String
     */
    protected function getBackLinkVirtua($networkCode, $institutionCode, $item, array $data)
    {
        $values = array(
            'server' => $data['domain'],
            'language-code' => 'de', // @todo fetch from user
            //'bib-system-number' => $this->getNumericString($item['bibsysnumber']) // remove characters from number string
        );

        return $this->compileString($data['pattern'], $values);
    }


    /**
     * Get back link for alexandria
     * Currently only a wrapper for virtua
     *
     * @param    String $networkCode
     * @param    String $institutionCode
     * @param    Array $item
     * @param    Array $data
     * @return    String
     */
    protected function getBackLinkAlex($networkCode, $institutionCode, array $item, array $data)
    {
        return $this->getBackLinkVirtua($networkCode, $institutionCode, $item, $data);
    }


    /**
     * Get back link for SNL
     * Currently only a wrapper for virtua
     *
     * @param    String $networkCode
     * @param    String $institutionCode
     * @param    Array $item
     * @param    Array $data
     * @return    String
     */
    protected function getBackLinkSNL($networkCode, $institutionCode, $item, array $data)
    {
        return $this->getBackLinkVirtua($networkCode, $institutionCode, $item, $data);
    }

    /**
     * Get back link for CCSA (poster collection)
     * Currently only a wrapper for virtua
     *
     * @param    String $networkCode
     * @param    String $institutionCode
     * @param    Array $item
     * @param    Array $data
     * @return    String
     */

    protected function getBackLinkCCSA($networkCode, $institutionCode, $item, array $data)
    {
        return $this->getBackLinkVirtua($networkCode, $institutionCode, $item, $data);
    }

    /**
     * Build rero backlink
     *
     * @param       $networkCode
     * @param       $institutionCode
     * @param       $item
     * @param array $data
     * @return mixed
     */
    protected function getBackLinkRERO($networkCode, $institutionCode, $item, array $data)
    {
        $values = array(
            'server' => $data['domain'],
            'language-code' => 'de', // @todo fetch from user,
            'RERO-network-code' => (int)substr($institutionCode, 2, 2), // third and fourth character
            //'bib-system-number' => $item['bibsysnumber'], // replaces the incorrect version: 'bib-system-number' => $this->getNumericString($item['bibsysnumber']), // remove characters from number string
            'sub-library-code' => $this->getNumericString($institutionCode) //removes the RE-characters from the number string
        );

        return $this->compileString($data['pattern'], $values);
    }

    /**
     * Get back link for helvetic archives
     * Currently only a wrapper for virtua
     *
     * @param    String $networkCode
     * @param    String $institutionCode
     * @param    Array $item
     * @param    Array $data
     * @return    String
     */
    protected function getBackLinkCHARCH($networkCode, $institutionCode, array $item, array $data)
    {
        return $this->getBackLinkVirtua($networkCode, $institutionCode, $item, $data);
    }

    protected function getBackLinkIDSSG($networkCode, $institutionCode, array $item, array $data)
    {
        return $this->getBackLinkAleph($networkCode, $institutionCode, $item, $data);
    }


    /**
     * Compile string. Replace {varName} pattern with names and data from array
     *
     * @param    String $string
     * @param    Array $keyValues
     * @return    String
     */
    protected function compileString($string, array $keyValues)
    {
        $newKeyValues = array();

        foreach ($keyValues as $key => $value) {
            $newKeyValues['{' . $key . '}'] = $value;
        }

        return str_replace(array_keys($newKeyValues), array_values($newKeyValues), $string);
    }


    /**
     * Remove all not-numeric parts from string
     *
     * @param    String $string
     * @return    String
     */
    protected function getNumericString($string)
    {
        return preg_replace('[\D]', '', $string);
    }



}


