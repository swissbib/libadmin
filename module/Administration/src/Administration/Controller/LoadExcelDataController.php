<?php
namespace Administration\Controller;


/**
 * LoadExcelDataController
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 25.04.18
 * Time: 15:02
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
 * @package  Administration_Controller
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */
use Administration\Services\ImportExportService;
use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Zend\Console\Request as ConsoleRequest;




/**
 * LoadExcelDataController
 *
 * @category Swissbib_VuFind2
 * @package  Administration_Controller
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class LoadExcelDataController extends AbstractConsoleController
{

    /**
     * @var ImportExportService
     */
    private $importExportService;

    public function __construct(ImportExportService $importExportservice)
    {
        $this->importExportService = $importExportservice;
    }


    public function loadadmindataAction() {
        $test = "";
    }



    public function loaddataAction ()
    {

        $request = $this->getRequest();

        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof ConsoleRequest) {
            throw new \Error(
                'You can only use this action from a console!'
            );
        }


        $filename = $this->params("filename");

        $params = $request->getParams()->getArrayCopy();

        if (in_array("institution", $params))
        {
            $this->importExportService->loadExcelDataInstitution($filename);

        } elseif (in_array("admininstitution", $params)) {
            $this->importExportService->loadExceldataAdminInstitution($filename);
        } else {
            return "wrong route, no service match";
        }



        return "wow - does this work? - tomorrow we will see ....\n";

    }


}