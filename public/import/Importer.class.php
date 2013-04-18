<?php

/**
 * Import data from XML file into Database
 */
class Importer
{

	/**
	 * @var    Array
	 */
	private $dbConfig;

	/**
	 * @var    DB
	 */
	private $DB;

	/**
	 * @var    Array    Keys of languages to be imported
	 */
	private $languageKeys = array('de', 'fr', 'it', 'en');



	/**
	 * Constructor
	 *
	 * @throws    Exception
	 * @param    Array        $dbConfig
	 * @param    Boolean        $flush        Delete existing institution records initially?
	 */
	public function __construct(array $dbConfig, $flush)
	{
		$this->setDbConfig($dbConfig);

		try {
			$this->connectToDatabase();
			if ($flush) {
				$this->flushInstitutions();
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}



	/**
	 * @param    Array        $dbConfig
	 * @throws    Exception
	 */
	public function setDbConfig(array $dbConfig)
	{
		if (!array_key_exists('dsn', $dbConfig)
				|| !array_key_exists('username', $dbConfig)
				|| !array_key_exists('password', $dbConfig)
		) {
			throw new Exception('Invalid database configuration.');
		}

		$this->dbConfig = $dbConfig;
	}



	/**
	 * Flush existing institution records
	 */
	public function flushInstitutions()
	{
		$this->DB->query('DELETE FROM mm_institution_group_view WHERE 1');
		$this->DB->query('DELETE FROM institution WHERE 1');
	}



	/**
	 * @throws    Exception
	 * @param    String        $file            XML file to be imported
	 * @return    String
	 */
	public function import($file)
	{
		try {
			/** @var $xml SimpleXMLElement */
			$xml = $this->initSimpleXmlFromFromFile($file);
			$this->importLibraries($xml->libraries);
//			$queryLogfile	= $this->DB->storeQueryLog();
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

		return 'Data from "' . basename($file) . '" has been successfully imported.';
	}



	/**
	 * Import nodes from given SimpleXMLElement into given table
	 *
	 * @throws    Exception
	 * @param    SimpleXMLElement[]    $xmlNodes
	 * @param    String                $table
	 */
	private function importLibraries($xmlNodes, $table = 'institution')
	{
		foreach ($xmlNodes->children() as $library) {
			$fieldsValues = array(
//				'id'			=> (string) $library->id,
				'bib_code' => (string)$library->libraryIdentifier,
				'sys_code' => (string)$library->name,
				'is_active' => 1,
				'address' => (string)$library->road,
				'zip' => (string)$library->zipCode,
				'city' => (string)$library->town,
				'country' => 'ch',
				'canton' => DataHelper::getCantonFromZip((string)$library->zipCode),
				'website' => (string)$library->addressURL,
				'email' => '',
				'phone' => '',
				'skype' => '',
				'facebook' => '',
				'coordinates' => '',
				'isil' => DataHelper::getISIL((string)$library->libraryIdentifier, 'ch'),
				'notes' => '',
			);
			// Add label translations
			$translations = $library->translation->translations;
			foreach ($translations->children() as $translation) {
				$languageKey = strtolower((string)$translation->key);
				if (in_array($languageKey, $this->languageKeys)) {
					$fieldsValues['label_' . $languageKey] = (string)$translation->value;
				}
			}

			// Add URLs
			$url = (string)$library->url;
			foreach ($this->languageKeys as $key) {
				$fieldsValues['url_' . $key] = $url;
			}

			try {
				$this->DB->execInsert($fieldsValues, $table);
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
	}



	/**
	 * Validate XML file via DTD, create SimpleXMLElement from it
	 * Precondition: "markup.dtd" must exist in the same folder as the XML file
	 *
	 * @throws    Exception
	 * @param    String                $file
	 * @return    SimpleXMLElement    SimpleXMLElement Object from contents of given XML file
	 */
	private function initSimpleXmlFromFromFile($file)
	{
		try {
			$xmlString = $this->getFileContents($file);
			// Ensure XML to be not empty
			if (empty($xmlString)) {
				throw new Exception('Import file: "' . basename($file) . '" is empty!');
			}
			// Ensure XML to be valid - load and insert DTD
			$dtd = $this->getFileContents(dirname($file) . DIRECTORY_SEPARATOR . 'markup.dtd');
			$xmlString = str_replace('<libraryconfiguration>', $dtd . '<libraryconfiguration>', $xmlString);
			// Validate XML from DTD
			$dom = new DOMDocument;
			$dom->loadXML($xmlString);
			if (!$dom->validate()) {
				throw new Exception('Invalid XML in file: "' . basename($file) . '".');
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

		return simplexml_load_string($xmlString);
	}



	/**
	 * @throws    Exception
	 * @param    String        $file
	 * @return    String        Contents of given file
	 */
	private function getFileContents($file)
	{
		if (!is_file($file)) {
			throw new Exception('File not found: ' . $file);
		}

		return file_get_contents($file);
	}



	/**
	 * Connect to MySql database
	 *
	 * @throws    Exception
	 */
	public function connectToDatabase()
	{
		$dsn = $this->dbConfig['dsn'];
		// Parse host and database name out of data source name
		$dsnAttributes = explode(':', $dsn);
		$HostAndDB = array();

		foreach ($dsnAttributes as $element) {
			$element = strtolower($element);
			if (preg_match('/dbname/', $element) && preg_match('/host/', $element)) {
				$singleAttributes = explode(';', $element);
				foreach ($singleAttributes as $attribute) {
					$tAttribute = explode('=', $attribute);
					$HostAndDB[$tAttribute[0]] = $tAttribute[1];
				}
			}
		}
		if (sizeof($HostAndDB) == 0) {
			throw (new Exception ('Wrong or missing definition of dbName and / or host name'));
		}

		try {
			$this->DB = new DB(
				$HostAndDB['host'],
				$HostAndDB['dbname'],
				$this->dbConfig['username'],
				$this->dbConfig['password']
			);
		} catch (Exception $e) {
			throw(new Exception($e->getMessage()));
		}
	}
}
