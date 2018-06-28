<?php

/**
 * GroupControllerFactory
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 26.01.18
 * Time: 11:40
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
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */

namespace Libadmin\Controller;

use Interop\Container\ContainerInterface;
use Libadmin\Form\AdminInstitutionForm;
use Libadmin\Table\AdminInstitutionTable;
use Libadmin\Table\AdresseTable;
use Libadmin\Table\InstitutionAdminInstitutionRelationTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\KontaktTable;
use Libadmin\Table\KostenbeitragTable;
use Libadmin\Table\TablePluginManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * AdminInstitutionControllerFactory
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Controller
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class AdminInstitutionControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $formElementManager = $container->get('FormElementManager');

        /**
         * TablePluginManager
         *
         * @var TablePluginManager $tablePluginManager tablepluginmanager
         */
        $tablePluginManager =  $container->get(TablePluginManager::class);

        $institutionForm = $formElementManager->get(AdminInstitutionForm::class);
        $adminInstitutionTable = $tablePluginManager->get(AdminInstitutionTable::class);
        $institutionTable = $tablePluginManager->get(InstitutionTable::class);


        $relationInstitutionAdminInstTable = $tablePluginManager->get(InstitutionAdminInstitutionRelationTable::class);

        return new AdminInstitutionController(
            $institutionForm,
            $adminInstitutionTable,
            $relationInstitutionAdminInstTable,
            $institutionTable
        );


    }
}