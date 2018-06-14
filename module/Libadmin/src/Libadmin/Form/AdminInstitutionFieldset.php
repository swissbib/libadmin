<?php

/**
 * AdminInstitutionFieldset
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 29.03.18
 * Time: 14:58
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

use Libadmin\Model\AdminInstitution;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;

/**
 * AdminInstitutionFieldset
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Form
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class AdminInstitutionFieldset extends BaseFieldset implements InputFilterProviderInterface
{
    public function init()
    {
        $this->setName('admininstitution');

        $this->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new AdminInstitution());

        $this->addHidden('id');

        $this->addText('name', 'name', true);
        $this->addText('email', 'email', true);
        $this->addText('korrespondezsprache', 'korrespondezsprache', true);
        $this->addText('bfscode', 'bfscode', true);
        $this->addText('ipadresse', 'ipadresse', true);
        $this->addText('zusage_beitrag', 'zusage_beitrag', true);
        $this->addText('bemerkung_kostenbeitrag', 'bemerkung_kostenbeitrag', true);
        $this->addText('adresse_rechnung_gleich_post', 'adresse_rechnung_gleich_post', true);
        $this->addText('mwst', 'mwst', true);
        $this->addText('grund_mwst_frei', 'grund_mwst_frei', true);
        $this->addText('e_rechnung', 'e_rechnung', true);
        $this->addText('bemerkung_rechnung', 'bemerkung_rechnung', true);



    }



    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [];
    }
}