<?php
namespace Libadmin\Form;

use Traversable;
use Zend\Stdlib\ArrayUtils;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Element;

use Libadmin\Form\BaseForm;
use Libadmin\Table\ViewTable;
use Libadmin\Model\Group;
use Libadmin\Model\View;
use Libadmin\Form\Element\NoValidationMultiCheckbox;
use Libadmin\Form\GroupFieldset;
use Libadmin\Model\Institution;

/**
 * Group form
 *
 */
class GroupForm extends BaseForm
{

	protected $views;


	protected $institutions;


	/**
	 * Initialize
	 * Inject view table
	 *
	 * @param    View[]			$views
	 * @param    Institution[]	$institutions
	 * @param    String|Null	$name
	 * @param    Array			$options
	 */
	public function __construct(array $views, array $institutions, $name = null, $options = array())
	{
		parent::__construct('group', $options);

		$this->views		= $views;
		$this->institutions	= $institutions;

		$this->setHydrator(new ClassMethodsHydrator(false));

		$fieldset	= new GroupFieldset($views);
		$fieldset->setUseAsBaseFieldset(true);

		$this->add($fieldset);
	}



	/**
	 * Set data
	 * Make sure views data is always set
	 *
	 * @param    Array|\ArrayAccess|\Traversable    $data
	 * @return    GroupForm
	 */
	public function setData($data)
	{
		if ($data instanceof Traversable) {
			$data = ArrayUtils::iteratorToArray($data);
		}

		if (!isset($data['group']['views'])) {
			$data['group']['views'] = array();
		}

		return parent::setData($data);
	}



	/**
	 *
	 *
	 * @return	View[]
	 */
	public function getViews()
	{
		return $this->views;
	}



	/**
	 *
	 *
	 * @param	Integer		$index
	 * @return	View
	 */
	public function getView($index)
	{
		return $this->views[$index];
	}


	public function getInstitutions()
	{
		return $this->institutions;
	}



	/**
	 * @param	Integer		$idInstitution
	 * @return	Institution
	 */
	public function getInstitution($idInstitution = null)
	{
		return isset($this->institutions[$idInstitution]) ? $this->institutions[$idInstitution] :  null;
	}
}
