<?php

/**
 * InstitutionControllerFactory
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 26.01.18
 * Time: 11:35
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
use Interop\Container\Exception\ContainerException;
use Libadmin\Form\InstitutionForm;
use Libadmin\Table\InstitutionRelationTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\ViewTable;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Form\FormElementManagerFactory;
use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Libadmin\Table\TablePluginManager;
use Zend\I18n\Translator\TranslatorInterface;

/**
 * InstitutionControllerFactory
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Controller
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class InstitutionControllerFactory implements FactoryInterface
{

    /**
     * Invoke Method
     *
     * @param ContainerInterface $container     container
     * @param string             $requestedName requestedName
     * @param array|null         $options       options
     *
     * @return InstitutionController
     */
    public function __invoke(
        ContainerInterface $container, $requestedName, array $options = null
    ) {
        $formElementManager = $container->get('FormElementManager');

        /**
         * TablePluginManager
         *
         * @var TablePluginManager $tablePluginManager tablepluginmanager
         */
        $tablePluginManager =  $container->get(TablePluginManager::class);

        $institutionForm = $formElementManager->get(InstitutionForm::class);
        $institutionTable = $tablePluginManager->get(InstitutionTable::class);
        $institutionRelationTable
            = $tablePluginManager->get(InstitutionRelationTable::class);
        /** @var ViewTable $viewTable */
        $viewTable = $tablePluginManager->get(ViewTable::class);
        $allViews = $viewTable->getAllViewsOptions();

        return new InstitutionController(
            $institutionForm,
            $institutionTable,
            $institutionRelationTable,
            $allViews
        );
    }
}