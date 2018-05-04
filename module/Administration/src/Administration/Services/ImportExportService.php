<?php

/**
 * ImportExportService
 *
 * PHP version 5
 *
 * Copyright (C) project swissbib, University Library Basel, Switzerland
 * http://www.swissbib.org  / http://www.swissbib.ch / http://www.ub.unibas.ch
 *
 * Date: 25.04.18
 * Time: 17:04
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
 * @package  Administration_Services
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.swissbib.org
 */

namespace Administration\Services;
use Libadmin\Model\AdminInstitution;
use Libadmin\Model\Adresse;
use Libadmin\Model\Institution;
use Libadmin\Model\Kontakt;
use Libadmin\Model\Kostenbeitrag;
use Libadmin\Table\AdminInstitutionTable;
use Libadmin\Table\AdresseTable;
use Libadmin\Table\InstitutionAdminInstitutionRelationTable;
use Libadmin\Table\InstitutionTable;
use Libadmin\Table\KontaktTable;
use Libadmin\Table\KostenbeitragTable;
use Zend\Config\Reader\Ini;
use Zend\Config\Config;

/**
 * ImportExportService
 *
 * @category Swissbib_VuFind2
 * @package  Administration_Services
 * @author   Günter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class ImportExportService
{


    private $firstLineHeaders = 'bibcode;labelDE;name';

    private $firstLineHeadersAdminInstitution = "code;name;adresse";

    private $keysInstitution = [
        "bib_code", "label_de", "name_de", "address_strasse", "address_nummer", "address_zusatz",
        "zip", "city", "country", "mail", "kontakt_name", "kontakt_vorname", "kontakt_anrede", "kontakt_email",
        "korrespondenzsprache", "bfs_code", "worldcat_ja_nein", "worldcat_symbol", "cbs_library_code",
        "verrechnung_kostenbeitrag_auswahl", "kostenbeitrag_basiert_auf", "zusage_kostenbeitrag_ja_nein",
        "beitrag_2018", "beitrag_2019", "beitrag_2020", "bemerkung_kostenbeitrag", "rechnungsadresse_gleich_postadresse_ja_nein",
        "rechnungsadresse_name", "rechnungsadresse_strasse", "rechnungsadresse_nummer", "rechnungsadresse_zusatz",
        "rechnungsadresse_plz", "rechnungsadresse_ort", "rechnungsadresse_country",
        "kontakt_rechnung_name", "kontakt_rechnung_vorname", "kontakt_rechnung_anrede", "kontakt_rechnung_mail",
        "mwst_ja_nein", "grund_mwst_befreiung", "e_rechnung_ja_nein", "bemerkung_rechnungsstellung",
        "koordinaten", "admininstitution_bfs_code", "kostenbeitrag_ueber_verbund_zugehoerigkeit",
        "kostenbeitrag_ueber_uni_fh_uni", "zugehoerigkeit_institution",
        "sap_name_1", "sap_name2", "sap_name3", "sap_name4"
    ];


    private $keysAdminInstitution = [
        "idcode", "name", "address_strasse", "address_nummer", "address_zusatz", "zip",
        "city", "country", "mail", "kontakt_name", "kontakt_vorname", "kontakt_anrede",
        "kontakt_email", "korrespondenzsprache", "bfs_code", "ipadresse", "zusage_kostenbeitrag_ja_nein",
        "kostenbeitrag_basiert_auf", "beitrag_2018", "beitrag_2019", "beitrag_2020", "bemerkung_kostenbeitrag",
        "rechnungsadresse_gleich_postadresse_ja_nein", "rechnungsadresse_name", "rechnungsadresse_strasse",
        "rechnungsadresse_nummer", "rechnungsadresse_zusatz", "rechnungsadresse_plz", "rechnungsadresse_ort",
        "rechnungsadresse_country", "kontakt_rechnung_name", "kontakt_rechnung_vorname", "kontakt_rechnung_anrede",
        "kontakt_rechnung_mail", "leere_spalte_export","mwst_ja_nein", "grund_mwst_befreiung", "e_rechnung_ja_nein",
        "bemerkung_rechnungsstellung", "zugehoerige_institutions", "relation_type"

    ];



    /**
     * @var InstitutionTable
     */
    private $institutionTable;
    /**
     * @var AdminInstitutionTable
     */
    private $adminInstitutionTable;
    /**
     * @var InstitutionAdminInstitutionRelationTable
     */
    private $institutionAdminInstitutionRelationTable;
    /**
     * @var KontaktTable
     */
    private $kontaktTable;
    /**
     * @var AdresseTable
     */
    private $adresseTable;
    /**
     * @var KostenbeitragTable
     */
    private $kostenbeitragTable;
    /**
     * @var array
     */
    private $config;

    public function __construct(
            InstitutionTable $institutionTable,
            AdminInstitutionTable $adminInstitutionTable,
            InstitutionAdminInstitutionRelationTable $institutionAdminInstitutionRelationTable,
            KontaktTable $kontaktTable,
            AdresseTable $adresseTable,
            KostenbeitragTable $kostenbeitragTable,
            array $config
    )
    {


        $this->institutionTable = $institutionTable;
        $this->adminInstitutionTable = $adminInstitutionTable;
        $this->institutionAdminInstitutionRelationTable = $institutionAdminInstitutionRelationTable;
        $this->kontaktTable = $kontaktTable;
        $this->adresseTable = $adresseTable;
        $this->kostenbeitragTable = $kostenbeitragTable;

        $reader = new Ini();
        $this->config   =  new Config($reader->fromFile($config['exportimportconfig']));

    }

    public function loadExcelDataInstitution(string $filename)
    {

        $wrongMatch = 0;
        $processed = 0;
        foreach ($this->readsingleLines($filename) as $line) {
            //php and utf-8 - really nice.... by the way: iI have to confess: I do not understand
            //how these things are done in PHP...
            //compare
            //https://stackoverflow.com/questions/35679900/how-fix-utf-8-characters-in-php-file-get-contents
            $line = mb_convert_encoding($line, 'UTF-8',mb_detect_encoding($line, 'UTF-8, ISO-8859-1', true));
            if ($this->checkFirstLine($line))
                continue 1;

            $splittedLine = $this->splitLine($line);

            $combinedValuesFromLine = array_combine($this->keysInstitution,$splittedLine);

            if (!is_array($combinedValuesFromLine)) {
                $wrongMatch++;
                continue 1;
            }

            if ($this->isBibCodeAvailable($combinedValuesFromLine)) {


                /**
                 * @var $institution \Libadmin\Model\Institution
                 */
                $institution =  $this->getInstitutionWithBibCode($combinedValuesFromLine);
                $institution->initLocalVariablesFromExcel($combinedValuesFromLine);

                //todo: setze alte Adressbestandteile auf Null
                //füge Kostenbeiträge hinzu
                //relation zu admininstitution
                //sammle skipped lines und logge diese
                if (empty($institution->getId_postadresse()) ) {
                    //todo: ich habe noch nicht den Fall abgefangen, bei dem bereits eine Adresse vorhanden ist
                    //(Datenbankupdate durch Importdaten)

                    $postAdresse = new Adresse();
                    $postAdresse->initLocalVariablesFromExcel($combinedValuesFromLine);
                    //canton is not part of Excel file and we want to remove the adress part from the institution table
                    //and move it into the adress table
                    $postAdresse->setCanton($institution->getCanton());
                    $postAdressID = $this->adresseTable->save($postAdresse);

                    $institution->setIdPostadresse($postAdressID);
                }

                if (! $institution->getAdresse_rechnung_gleich_post() ) {
                    $rechnungsAdresse = new Adresse();
                    $rechnungsAdresse->initLocalVariablesFromExcelRechnungsadresse($combinedValuesFromLine);
                    //canton is not part of Excel file and we want to remove the adress part from the institution table
                    //and move it into the adress table
                    $rechnungsAdresse->setCanton($institution->getCanton());
                    $rechnungsadressID = $this->adresseTable->save($rechnungsAdresse);

                    $institution->setIdRechnungsadresse($rechnungsadressID);

                }

                if (empty($institution->getId_kontakt()) && !empty ($combinedValuesFromLine["kontakt_name"]))
                {
                    $mainContact = new Kontakt();
                    $mainContact->initLocalVariablesFirstPersonFromExcel($combinedValuesFromLine);
                    $idMainContact =  $this->kontaktTable->save($mainContact);
                    $institution->setIdKontakt($idMainContact);

                }

                if (empty($institution->getId_kontakt_rechnung()) && !empty ($combinedValuesFromLine["kontakt_rechnung_name"]))
                {
                    $billContact = new Kontakt();
                    $billContact->initLocalVariablesBillPersonFromExcel($combinedValuesFromLine);
                    $idBillContact =  $this->kontaktTable->save($mainContact);
                    $institution->setIdKontaktRechnung($idBillContact);

                }

                if ($this->checkInsertBeitraegeForInstitution($institution,$combinedValuesFromLine)) {
                    $beitraege = new Kostenbeitrag();
                    $beitraege->initLocalVariablesFromExcel($combinedValuesFromLine);
                    $idKostenbeitrag =  $this->kostenbeitragTable->save($beitraege);
                    $institution->setIdKostenbeitrag($idKostenbeitrag);

                }


                $this->institutionTable->saveInstitutionOnly($institution);


                $processed++;
                $test = "";

                //if (!empty($combinedValuesFromLine["beitrag_2018"])) {
                //    $test = $combinedValuesFromLine["beitrag_2018"];
                //}

            }

        }
        var_dump("processed " . $processed . " lines");
        var_dump( $wrongMatch . " lines skipped - something is not correct with content on these lines (too much commas probably)");

    }


    public function loadExceldataAdminInstitution(String $filename)
    {
        $wrongMatch = 0;
        $processed = 0;
        foreach ($this->readsingleLines($filename) as $line) {
            //php and utf-8 - really nice.... by the way: iI have to confess: I do not understand
            //how these things are done in PHP...
            //compare
            //https://stackoverflow.com/questions/35679900/how-fix-utf-8-characters-in-php-file-get-contents
            $line = mb_convert_encoding($line, 'UTF-8', mb_detect_encoding($line, 'UTF-8, ISO-8859-1', true));
            if ($this->checkFirstLineAdminInstitution($line)) {
                $testsplit = $this->splitLine($line);
                continue 1;
            }
            $splittedLine = $this->splitLine($line);

            $combinedValuesFromLine = array_combine($this->keysAdminInstitution,$splittedLine);

            if (!is_array($combinedValuesFromLine)) {
                $wrongMatch++;
                continue 1;
            }

            $admininstitution =  new AdminInstitution();
            $admininstitution->initLocalVariablesFromExcel($combinedValuesFromLine);

            if (empty($admininstitution->getIdAdresse()) ) {
                //todo: ich habe noch nicht den Fall abgefangen, bei dem bereits eine Adresse vorhanden ist
                //(Datenbankupdate durch Importdaten)

                $postAdresse = new Adresse();
                $postAdresse->initLocalVariablesFromExcel($combinedValuesFromLine);
                //canton is not part of Excel file and we want to remove the adress part from the institution table
                //and move it into the adress table
                $postAdressID = $this->adresseTable->save($postAdresse);

                $admininstitution->setIdAdresse($postAdressID);
            }

            if (! $admininstitution->getAdresseRechnungGleichPost() ) {
                $rechnungsAdresse = new Adresse();
                $rechnungsAdresse->initLocalVariablesFromExcelRechnungsadresse($combinedValuesFromLine);
                //canton is not part of Excel file and we want to remove the adress part from the institution table
                //and move it into the adress table
                $rechnungsadressID = $this->adresseTable->save($rechnungsAdresse);

                $admininstitution->setIdRechnungsadresse($rechnungsadressID);

            }



            //todo: relations zu institution
            //kostenbeitraege

            $this->adminInstitutionTable->save($admininstitution);
        }
    }



    private function readsingleLines($filename) {

        $datadir = $this->config->DATA_DIRS->excelimport;
        $completePath =  $datadir . DIRECTORY_SEPARATOR . $filename;
        $file = fopen($completePath, 'r');

        if (!$file)
            throw new \Error("file: " . $filename . " does not exist in dir...");

        while (($line = fgets($file)) !== false) {
            yield $line;
        }

        fclose($file);

    }


    private function checkFirstLine($line):bool
    {
        return strpos($line, $this->firstLineHeaders) === false ? false : true ;

    }


    private function checkFirstLineAdminInstitution($line):bool
    {
        return strpos($line, $this->firstLineHeadersAdminInstitution) === false ? false : true ;

    }



    private function splitLine($line)
    {
        return explode(";",$line);
    }

    private function insertIntoDB ($valuesFromFile)
    {

    }

    private function isBibCodeAvailable(array $combinedValues): bool
    {
        return $this->institutionTable->bibCodeExists($combinedValues["bib_code"]);
    }


    private function getInstitutionWithBibCode(array $combinedValues)
    {
        return $this->institutionTable->getInstitutionBasedOnField($combinedValues["bib_code"]);
    }

    private function checkInsertBeitraegeForInstitution(Institution $institution, array $importData) : bool
    {
        $insertBeitrage = false;
        if ($institution->getZusage_beitrag())
            $insertBeitrage = true;
        if (!empty($importData["beitrag_2018"]) ||
            !empty($importData["beitrag_2019"]) ||
            !empty($importData["beitrag_2010"]))
            $insertBeitrage = true;

        return $insertBeitrage;
    }

}