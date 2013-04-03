<?php

namespace Libadmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;



/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/13/12
 * Time: 10:20 AM
 * To change this template use File | Settings | File Templates.
 */
class HomeController extends AbstractActionController
{

//    protected $libraryTable;

    public function indexAction()
    {
		return array('test'=>1);

//        $libraries = $this->getLibraryTable()->fetchAll();
//        $elements = array();
//        foreach ($libraries as $library) {
//            $library->parseContentXML();
//            $elements[] = $library;
//        }
//
//        return new ViewModel(array(
//            'libraries' => $elements,
//        ));


    }

//
//    public function getLibraryTable()
//    {
//        if (!$this->libraryTable) {
//            $sm = $this->getServiceLocator();
//            $this->libraryTable = $sm->get('Libraries\Model\LibrariesTable');
//        }
//        return $this->libraryTable;
//    }


}
