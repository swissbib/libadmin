<?php
namespace Libadmin\Form;

use Zend\Form\Element;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Db\ResultSet\ResultSet;

use Libadmin\Form\BaseForm;
use Libadmin\Form\InstitutionFieldset;
use Libadmin\Model\Group;
use Libadmin\Model\View;

/**
 * Form for institution
 * All fields are located in the institution fieldset
 * Initialized with the list of views for internal use
 *
 */
class InstitutionForm extends BaseForm
{

	/**
	 * @var    Array    Contains views
	 */
	public $views = array();

	public $groups = array();



	/**
	 * Initialize
	 *
	 * @param	View[]		$views
	 * @param	Group[]		$groups
	 */
	public function __construct(array $views, array $groups)
	{
		parent::__construct('institution');

		$this->setHydrator(new ClassMethodsHydrator(false));

		$this->views	= $views;
		$this->groups	= $groups;

		$fieldset = new InstitutionFieldset($views);
		$fieldset->setUseAsBaseFieldset(true);

		$this->add($fieldset);
	}
}
