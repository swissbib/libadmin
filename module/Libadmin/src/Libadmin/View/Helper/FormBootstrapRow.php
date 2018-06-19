<?php

/**
 * FormBootstrapRow
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 19.06.18
 * Time: 14:11
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
 * @package  Libadmin_Libadmin_View_Helper
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */

namespace Libadmin\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

/**
 * FormBootstrapRow
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Libadmin_View_Helper
 * @author   Lionel Walter <lionel.walter@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 */
class FormBootstrapRow extends AbstractHelper
{
    public function __invoke($fieldset, $field)
    {
        $viewModel = new ViewModel(
            [
                'fieldset' => $fieldset,
                'field'       => $field,
            ]
        );
        $viewModel->setTemplate('libadmin/global/form-bootstrap-row');
        return $this->getView()->render($viewModel);
    }
}