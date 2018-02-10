<?php

/**
 * ViewController
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 26.01.18
 * Time: 11:42
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Controller
 * @author   snowflake, Zürich
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */


namespace Libadmin\Controller;

use Libadmin\Form\ViewForm;
use Libadmin\Helper\RelationOverview;
use Libadmin\Model\View;
use Libadmin\Table\GroupTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\ViewTable;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;


/**
 * [Description]
 *
 */
class ViewController extends BaseController
{


    /**
     * @var ViewForm
     */
    private $viewForm;

    /**
     * @var ViewTable
     */
    private $viewTable;
    /**
     * @var GroupTable
     */
    private $groupTable;
    /**
     * @var InstitutionTable
     */
    private $institutionTable;
    /**
     * @var RelationOverview
     */
    private $relationOverview;


    /**
     * ViewController constructor.
     * @param ViewForm $viewForm
     * @param ViewTable $viewTable
     */
    public function __construct(
        ViewForm $viewForm,
        ViewTable $viewTable,
        GroupTable $groupTable,
        InstitutionTable $institutionTable,
        RelationOverview $relationOverview
    )
    {
        $this->viewForm = $viewForm;
        $this->viewTable = $viewTable;

        $this->groupTable = $groupTable;
        $this->institutionTable = $institutionTable;
        $this->relationOverview = $relationOverview;
    }




    /**
     * Add view
     *
     * @return    Response|ViewModel
     */
    public function addAction()
    {
        $form = $this->viewForm;
        /** @var Request $request */
        $request = $this->getRequest();
        $flashMessenger = $this->flashMessenger();

        if ($request->isPost()) {
            $view = new View();
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $view->exchangeArray($form->getData());
                $idView = $this->viewTable->save($view);

                $this->flashMessenger->addSuccessMessage('saved_view');

                return $this->redirectTo('edit', $idView);
            } else {
                $this->flashMessenger->addErrorMessage('form_invalid');
            }
        }

        $form->setAttribute('action', $this->makeUrl('view', 'add'));

        return $this->getAjaxView([
            'customform' => $form,
            'title' => 'view_add',
        ], 'libadmin/view/edit');
    }


    /**
     * Edit view
     *
     * @return    ViewModel
     */
    public function editAction()
    {
        $idView = (int)$this->params()->fromRoute('id', 0);

        if (!$idView) {
            return $this->forwardTo('home');
        }

        try {
            /** @var View $view */
            $view = $this->viewTable->getRecord($idView);
            $view->setGroups($this->viewTable->getGroupIDs($idView));
        } catch (\Exception $ex) {
            $this->flashMessenger->addErrorMessage('notfound_record');

            return $this->forwardTo('home');
        }

        $form = $this->viewForm;
        $form->bind($view);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost();
            $form->setData($postData);

            if ($form->isValid()) {
                $groupIdsSorted = $postData->get('groupsortableids');
                $institutionIdsSorted = $postData->get('institutionsortableids');
                $view = $form->getData();
                $view->setGroups($postData->get('groups') ?: []); // Workaround, if all groups are deselected

                $this->viewTable->save($view, $groupIdsSorted, $institutionIdsSorted);

                $form->bind($view);

                $this->flashMessenger->addSuccessMessage($this->translate('saved_view'));
            } else {
                $this->flashMessenger->addErrorMessage($this->translate('form_invalid'));
            }
        }

        $form->setAttribute('action', $this->makeUrl('view', 'edit', $idView));


        return $this->getAjaxView([
            'groups' => $this->groupTable->getViewGroups($idView),
            'institutions' => $this->institutionTable->getViewInstitutions($idView),
            'customform' => $form,
            'title' => 'view_edit',
            'relations' => $this->relationOverview->getData($view)
        ]);
    }


    /**
     * Before view delete, remove all relations
     *
     * @param    Integer $idView
     */
    protected function beforeDelete($idView)
    {
        $this->getGroupRelationTable()->deleteViewRelations($idView);
        $this->getInstitutionRelationTable()->deleteViewRelations($idView);
    }


    /**
     * Initial view
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'listItems' => $this->viewTable->getAll()
        ];
    }

}
