<?php

/**
 * InstitutionFormFactory
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 08.02.18
 * Time: 11:49
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
 * @package  Libadmin_Form
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */

namespace Libadmin\Form;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Libadmin\Table\AdminInstitutionTable;
use Libadmin\Table\GroupTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\TablePluginManager;
use Libadmin\Table\ViewTable;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * InstitutionFormFactory
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Form
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class InstitutionFormFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return InstitutionForm
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $tablePluginManager = $container->get(TablePluginManager::class);

        /** @var ViewTable $viewTable */
        $viewTable = $tablePluginManager->get(ViewTable::class);
        $allViews = $viewTable->getAllViewsOptions();

        /** @var GroupTable $groupTable */
        $groupTable = $tablePluginManager->get(GroupTable::class);
        $allGroups = $groupTable->getAllGroupsOptions();

        /** @var AdminInstitutionTable $adminInstitutionTable */
        $adminInstitutionTable = $tablePluginManager->get(AdminInstitutionTable::class);
        $allAdminInstitutions = $adminInstitutionTable->getAllAdminInstitutionsOptions();

        $form = new InstitutionForm($allViews, $allGroups, $allAdminInstitutions);
        $form->setHydrator(new ClassMethods(false));
        return $form;
    }
}