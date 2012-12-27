<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swissbib
 * Date: 12/13/12
 * Time: 2:59 PM
 * To change this template use File | Settings | File Templates.
 */

namespace  Libraries\Model;


use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import




class Library implements InputFilterAwareInterface
{

    public $libraryid;
    public $contentXML;
    public $tablekey;


    private $libraryElements;




    public function exchangeArray($data)
    {
        $this->libraryid     = (isset($data['libraryid'])) ? $data['libraryid'] : null;
        $this->contentXML = (isset($data['contentXML'])) ? $data['contentXML'] : null;
        $this->tablekey  = (isset($data['tablekey'])) ? $data['tablekey'] : null;

        $this->parseContentXML();

    }



    // Add content to this method:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();


            $inputFilter->add($factory->createInput(array(
                'name'     => 'tablekey',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));


            $inputFilter->add($factory->createInput(array(
                'name'     => 'libraryid',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 25,
                        ),
                    ),
                ),
            )));


            $inputFilter->add($factory->createInput(array(
                'name'     => 'contentXML',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                ),
            )));



            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


    public function parseContentXML() {

        try {
            $sxml = new \SimpleXMLElement($this->contentXML);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }


        $libraryItem = array();
        foreach($sxml->children() as $attr=>$val)
        {

            //echo $val;
            switch ($attr) {
                case "adress":
                    $adress = array();
                    foreach($val->children() as $adressPart => $val){
                        $adress[$adressPart] = (string)$val;
                    }

                    $libraryItem["adress"] = $adress;

                    break;
                case "translations":
                    $translations = array();
                    foreach($val->children() as $langName => $val){
                        $t =  (array) $val->attributes();
                        $lang_code = $t["@attributes"]["lang"];
                        $translations[$lang_code] = (string)$val;
                    }

                    $libraryItem["translations"] = $translations;

                    break;
                default:
                    $libraryItem[$attr] = (string)$val;
            }

        }

        $this->libraryElements = $libraryItem;


    }

    public function getLibraryElements()
    {
        return $this->libraryElements;
    }



}


class LibraryAdress {
    public $road;
    public $town;
    public $zipcode;
}

class Translations {
    public $name_de;
    public $name_fr;
    public $name_it;
    public $name_rr;
}
