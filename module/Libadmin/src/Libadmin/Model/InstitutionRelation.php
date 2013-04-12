<?php
namespace Libadmin\Model;

use Libadmin\Model\BaseModel;

/**
 * Relation between institution, group and view
 * Persisted in mm_institution_group_view
 *
 */
class InstitutionRelation extends BaseModel {

	/**
	 * @var	Integer
	 */
	public $id_view;

	/**
	 * @var	Integer
	 */
	public $id_group;

	/**
	 * @var	Integer
	 */
	public $id_institution;

	/**
	 * @var	Integer|Boolean
	 */
	public $is_favorite;

	/**
	 * @var	Integer
	 */
	public $position;

    public function getListLabel() {
        return 'Link info here';
    }



    public function getTypeLabel() {
        return 'Link';
    }



    public function setIdGroup($id_group) {
        $this->id_group = $id_group;
    }



    public function getIdGroup() {
        return (int)$this->id_group;
    }



    public function setIdInstitution($id_institution) {
        $this->id_institution = $id_institution;
    }



    public function getIdInstitution() {
        return (int)$this->id_institution;
    }



    public function setIdView($id_view) {
        $this->id_view = $id_view;
    }



    public function getIdView() {
        return (int)$this->id_view;
    }


	public function hasView() {
		return $this->getIdView() !== 0;
	}


    public function setIsFavorite($is_favorite) {
        $this->is_favorite = $is_favorite;
    }



    public function getIsFavorite() {
        return !!$this->is_favorite;
    }



    public function setPosition($position) {
        $this->position = $position;
    }



    public function getPosition() {
        return $this->position;
    }


}