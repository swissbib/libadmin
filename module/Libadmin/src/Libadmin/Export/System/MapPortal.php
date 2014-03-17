<?php
namespace Libadmin\Export\System;

use Zend\View\Model\JsonModel;
use Zend\Db\ResultSet\ResultSetInterface;

use Libadmin\Model\Group;
use Libadmin\Model\Institution;
#use ML\JsonLD\JsonLD;

/**
 * VuFind Export system
 *
 */
class MapPortal extends System
{

	/** @var InstitutionRelationTable */
	protected $institutionRelationTable;

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
			$groupData = array(
				'group' => $this->extractGroupData($group),
				'institutions' => array()
			);

			$institutions = $this->getGroupInstitutions($group);

			foreach ($institutions as $institution) {
				$groupData['institutions'][] = $this->$extractInstitutionMethod($institution);
			}

			$data[] = $groupData;
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
	protected function extractInstitutionData(Institution $institution)
	{
		return array(
			'id' 		=> $institution->getId(),
			'bib_code' 	=> $institution->getBib_code(),
			'sys_code' 	=> $institution->getSys_code(),
			'position' 	=> $institution->getPosition(),
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
			)
		);
	}



	/**
	 * Extract required data from institution
	 *
	 * @param    Institution        $institution
	 * @return    Array
	 */
	protected function extractAllInstitutionData(Institution $institution)
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
}
