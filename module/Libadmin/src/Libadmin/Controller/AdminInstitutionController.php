<?php
namespace Libadmin\Controller;

//use RecursiveIteratorIterator;

use Libadmin\Model\InstitutionRelation;
use Libadmin\Table\InstitutionRelationTable;
use Libadmin\Table\InstitutionTable;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Http\Request;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;
use Zend\Db\ResultSet\ResultSet;

use Libadmin\Form\InstitutionForm;
use Libadmin\Model\Institution;
use Libadmin\Controller\BaseController;
use Libadmin\Model\View;
use Libadmin\Model\Group;

/**
 * [Description]
 *
 */
class AdminInstitutionController extends BaseController
{

    public function __construct()
    {


    }

    /**
     * Initial view
     *
     * @return array
     */
    public function indexAction()
    {
        return [];

    }

}
