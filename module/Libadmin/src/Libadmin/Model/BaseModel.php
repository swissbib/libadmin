<?php
namespace Libadmin\Model;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\ArrayObject;

/**
 * [Description]
 *
 */
abstract class BaseModel
{




	/**
	 * @var InputFilter
	 */
	protected $inputFilter;



	/**
	 * Get all object vars as array
	 *
	 * @return array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}



	/**
	 * Get data for record without related tables (relations, kontakt, adresse)
	 *
	 * @return array
	 */
	public function getBaseData()
	{
		$data = $this->getArrayCopy();

		unset($data['id']);
		unset($data['inputFilter']);


		return $data;
	}



	public function exchangeArray($data)
	{
		if (is_object($data)) {
			$data = $data->getArrayCopy();
		}

		$this->initLocalVariables($data);
	}



	/**
	 * Get record ID
	 *
	 * @return    Integer
	 */
	public function getId()
	{
		return (int)$this->id;
	}



	public function setId($id)
	{
		$this->id = $id;
	}



	/**
	 * Initialize local variables if present
	 *
	 * @param  array $data
	 */
	protected function initLocalVariables(array $data)
	{
		foreach ($data as $key => $value) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}



	/**
	 * Get list label key
	 *
	 * @return    String
	 */
	public function getListLabel()
	{
		return get_class($this);
	}



	/**
	 * Get type label key
	 *
	 * @return    String
	 */
	public function getTypeLabel()
	{
		return get_class($this);
	}

	protected function formatKostenbeitragBasiertAuf($inputValue) : String
    {

        //todo: warum wird $formatKostenbeitrag als column der DB angesehen,
        //wenn ich diese als Klassenproperty definiere? Wie kann ich das ausschliessen?
        $formatKostenbeitrag = ["bfs-zahlen" => "bfs_zahlen",
        "anzahl aufnahmen" => "anzahl_aufnahmen",
        "freiwilliger beitrag" => "freiwilliger_beitrag",
        "recherchierte bfs-zahlen" => "recherchierte_bfs_zahlen"];


        return array_key_exists(strtolower($inputValue),$formatKostenbeitrag) ?
        $formatKostenbeitrag[strtolower($inputValue)] : "";
    }


    protected function formatKostenbeitragAdminBasiertAuf($inputValue) : String
    {

        //todo: warum wird $formatKostenbeitrag als column der DB angesehen,
        //wenn ich diese als Klassenproperty definiere? Wie kann ich das ausschliessen?
        $formatKostenbeitragAdmin = ["summe verbundbibliotheken" => "summe_verbundbibliotheken",
            "bfs-zahlen" => "bfs_zahlen",
            "anzahl aufnahmen" => "anzahl_aufnahmen",
            "freiwilliger beitrag" => "freiwilliger_beitrag",
            "recherchierte bfs-zahlen" => "recherchierte_bfs_zahlen"];


        return array_key_exists(strtolower($inputValue),$formatKostenbeitragAdmin) ?
            $formatKostenbeitragAdmin[strtolower($inputValue)] : "";
    }




    protected function formatVerrechnungsbeitrag($inputValue) : String
    {

        //todo: warum wird $formatKostenbeitrag als column der DB angesehen,
        //wenn ich diese als Klassenproperty definiere? Wie kann ich das ausschliessen?
        $formatVerrechnungsbeitrag = ["keine verrechnung" => "keine_verrechnung",
            "Über institution" => "ueber_institution",
            "direkt" => "direkt",
            "Über leitbibliothek" => "ueber_leitbibliothek"];


        return array_key_exists(strtolower($inputValue),$formatVerrechnungsbeitrag) ?
            $formatVerrechnungsbeitrag[strtolower($inputValue)] : "";
    }



}
