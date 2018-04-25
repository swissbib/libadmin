<?php
/**
 * ${NAME}
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 25.04.18
 * Time: 14:41
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
 * @package  ${PACKAGE}
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */


use Administration\Controller\LoadExcelDataController;
use Administration\Controller\LoadExcelDataControllerFactory;
use Administration\Services\ImportExportService;
use Administration\Services\ImportExportServiceFactory;



return [
    'controllers' => [
        'factories' => [
            LoadExcelDataController::class => LoadExcelDataControllerFactory::class,

        ]
    ],

    'console' => [
        'router' => [

            'routes' => [
                'load-exceldata' => [
                    'type'    => 'simple',
                    'options' => [
                        'route'  => 'loaddata [--verbose|-v] <filename>',
                        'defaults' => [
                            'controller' => LoadExcelDataController::class,
                            'action' => 'loaddata',
                        ],
                    ],
                ],
            ]
        ]
    ],


    'service_manager' => [
        'factories' => [

            ImportExportService::class  => ImportExportServiceFactory::class

        ]
    ]

];