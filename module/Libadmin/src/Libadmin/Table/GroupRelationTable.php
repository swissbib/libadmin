<?php
namespace Libadmin\Table;

use Zend\Db\TableGateway\TableGateway;

/**
 * Table for group relations
 *
 */
class GroupRelationTable
{

	/**
	 * @var    TableGateway    Fulltext search fields
	 */
	protected $tableGateway;



	/**
	 * Constructor
	 *
	 * @param	 TableGateway	$tableGateway
	 */
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}



	/**
	 * Delete all relations with selected group
	 *
	 * @param	Integer		$idGroup
	 */
	public function deleteGroupRelations($idGroup)
	{
		$this->tableGateway->delete(array(
										'id_group' => (int)$idGroup
									));
	}



	/**
	 * Delete all relations with selected view
	 *
	 * @param	Integer		$idView
	 */
	public function deleteViewRelations($idView)
	{
		$this->tableGateway->delete(array(
									'id_view' => (int)$idView
								));
	}
}
