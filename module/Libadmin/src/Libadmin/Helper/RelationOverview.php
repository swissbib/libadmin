<?php
namespace Libadmin\Helper;

use Libadmin\Model\Group;
use Libadmin\Model\Institution;
use Libadmin\Model\View;
use Libadmin\Table\GroupTable;
use Libadmin\Table\InstitutionTable;
use Zend\Db\ResultSet\ResultSetInterface;

/**
 * Fetch related institutions for a view
 * Group by groups
 *
 */
class RelationOverview
{

	/** @var View $view */
	protected $view;

	/** @var GroupTable $groupTable */
	protected $groupTable;

	/** @var InstitutionTable $institutionTable */
	protected $institutionTable;



	/**
	 * Initialize with required tables
	 *
	 * @param    GroupTable            $groupTable
	 * @param    InstitutionTable    $institutionTable
	 */
	public function __construct(GroupTable $groupTable, InstitutionTable $institutionTable)
	{
		$this->groupTable = $groupTable;
		$this->institutionTable = $institutionTable;
	}



	/**
	 * Get relations grouped by groups
	 *
	 * @param    View    $view
	 * @return    Array[]
	 */
	public function getData(View $view)
	{
		$this->view = $view;

		$data = array();
		$groups = $this->getGroups();

		foreach ($groups as $group) {
			/** @var Group $group */
			$data[] = array(
				'group' => $group,
				'institutions' => $this->getInstitutions($group->getId())
			);
		}

		return $data;
	}



	/**
	 * Get groups for view
	 *
	 * @param    Boolean        $activeOnly
	 * @return    null|ResultSetInterface
	 */
	protected function getGroups($activeOnly = true)
	{
		return $this->groupTable->getAllViewGroups($this->view->getId(), $activeOnly);
	}



	/**
	 * Get institutions for group in view
	 *
	 * @param    Integer        $idGroup
	 * @param    Boolean        $activeOnly
	 * @return    Institution[]
	 */
	protected function getInstitutions($idGroup, $activeOnly = true)
	{
		$results = $this->institutionTable->getAllGroupViewInstitutions($this->view->getId(), $idGroup, $activeOnly);
		$institutions = array();

		foreach ($results as $result) {
			$institutions[] = $result;
		}

		return $institutions;
	}
}
