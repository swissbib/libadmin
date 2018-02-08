<?php
namespace Libadmin\Form;

use Libadmin\Model\Group;
use Libadmin\Model\View;
use Zend\Form\Element;

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
     * @param View[] $views
     * @param Group[] $groups
     */
    public function __construct(array $views, array $groups)
    {
        parent::__construct('institution');



        $this->views = $views;
        $this->groups = $groups;
    }

    public function init()
    {
        $fieldset = new InstitutionFieldset($this->views);
        $fieldset->setUseAsBaseFieldset(true);

        $this->add($fieldset);
    }

}
