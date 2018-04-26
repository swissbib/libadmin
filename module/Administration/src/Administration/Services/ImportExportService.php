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

    public function loadExcelData(string $filename)
    {

        $count = 0;
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
                $count++;
                continue 1;
            }

            if ($this->isBibCodeAvailable($combinedValuesFromLine)) {


                $institution =  $this->getInstitutionWithBibCode($combinedValuesFromLine);
                $processed++;
                $test = "";

                //if (!empty($combinedValuesFromLine["beitrag_2018"])) {
                //    $test = $combinedValuesFromLine["beitrag_2018"];
                //}

            }

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



}