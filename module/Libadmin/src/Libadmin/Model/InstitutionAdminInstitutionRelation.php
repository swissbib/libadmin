<?php

namespace Libadmin\Model;
use Zend\Db\Sql\Ddl\Column\Integer;


/**
 * InstitutionAdminInstitutionRelation
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 25.04.18
 * Time: 17:43
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
 * @package  Libadmin_Model
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */


/**
 * InstitutionAdminInstitutionRelation
 *
 * @category Swissbib_VuFind2
 * @package  Libadmin_Model
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class InstitutionAdminInstitutionRelation extends BaseModel
{

    /**
     * @var ?Integer
     */
    public $id_institution;

    /**
     * @var ?Integer
     */
    public $id_admininstitution;


    /**
     * @var ?String
     */
    public $relation_type;

    /**
     * @return mixed
     */
    public function getIdInstitution()
    {
        return $this->id_institution;
    }

    /**
     * @param mixed $id_institution
     *
     * @return InstitutionAdminInstitutionRelation
     */
    public function setIdInstitution($id_institution)
    {
        $this->id_institution = $id_institution;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdAdmininstitution()
    {
        return $this->id_admininstitution;
    }

    /**
     * @param mixed $id_admininstitution
     *
     * @return InstitutionAdminInstitutionRelation
     */
    public function setIdAdmininstitution($id_admininstitution)
    {
        $this->id_admininstitution = $id_admininstitution;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelationType()
    {
        return $this->relation_type;
    }

    /**
     * @param mixed $relation_type
     *
     * @return InstitutionAdminInstitutionRelation
     */
    public function setRelationType($relation_type)
    {
        $this->relation_type = $relation_type;
        return $this;
    }




}