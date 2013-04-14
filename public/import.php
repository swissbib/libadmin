<?php
	// No warnings please
error_reporting(E_ERROR);
	// Start looking for files from root dir
chdir(dirname(__DIR__));

	// Take database credentials from ZF2 local config
$zf2LocalConf	= include('config/autoload/local.php');
try {
	$importer	= new Importer($zf2LocalConf['db']);
	$xmlFile	= 'module/Libadmin/data/tpgreen-libraries.xml';

	echo $importer->import('module/Libadmin/data/tpgreen-libraries.xml');

} catch(Exception $e) {
	die('IMPORT FAILED - ' . $e->getMessage() );
}



/**
 * Import data from XML file into Database
 */
class Importer {

	/**
	 * @var	Array
	 */
	private $dbConfig;

	/**
	 * @var	DB
	 */
	private $DB;

	/**
	 * @var	Array	Keys of languages to be imported
	 */
	private $languageKeys	= array('de', 'fr', 'it', 'en');

	/**
	 * Constructor
	 */
	public function __construct(array $dbConfig) {
		$this->setDbConfig($dbConfig);
	}

	/**
	 * @param	Array		$dbConfig
	 * @throws	Exception
	 */
	public function setDbConfig(array $dbConfig) {
		if( 	!array_key_exists('dsn', $dbConfig)
			||	!array_key_exists('username', $dbConfig)
			||	!array_key_exists('password', $dbConfig)
		) {
			throw new Exception('Invalid database configuration.');
		}

		$this->dbConfig	= $dbConfig;
	}

	/**
	 * @throws	Exception
	 * @param	String		$file			XML file to be imported
	 * @return	String
	 */
	public function import($file) {
		try {
			$this->connectToDatabase();

			/** @var $xml SimpleXMLElement */
			$xml	= $this->initSimpleXmlFromFromFile($file);
			$this->importLibraries( $xml->libraries );
//			$queryLogfile	= $this->DB->storeQueryLog();
		} catch(Exception $e) {
			throw new Exception( $e->getMessage() );
		}

		return	'Data from "' . basename($file) . '" has been successfully imported.';
	}

	/**
	 * Import nodes from given SimpleXMLElement into given table
	 *
	 * @throws	Exception
	 * @param	SimpleXMLElement[]	$xmlNodes
	 * @param	String				$table
	 */
	private function importLibraries($xmlNodes, $table	= 'institution') {
		foreach($xmlNodes->children() as $library) {
			$fieldsValues	= array(
//				'id'			=> (string) $library->id,
				'bib_code'		=> (string) $library->libraryIdentifier,
				'sys_code'		=> (string) $library->name,
				'is_active'		=> 1,
				'address'		=> (string) $library->road,
				'zip'			=> (string) $library->zipCode,
				'city'			=> (string) $library->town,
				'country'		=> 'ch',
				'canton'		=> dataHelper::getCantonFromZip( (string) $library->zipCode ),
				'website'		=> (string) $library->addressURL,
				'email'			=> '',
				'phone'			=> '',
				'skype'			=> '',
				'facebook'		=> '',
				'coordinates'	=> '',
				'isil'			=> dataHelper::getISIL( (string) $library->libraryIdentifier, 'ch' ),
				'notes'			=> '',
			);
				// Add label translations
			$translations	= $library->translation->translations;
			foreach($translations->children() as $translation) {
				$languageKey= strtolower((string) $translation->key);
				if( in_array($languageKey, $this->languageKeys) ) {
					$fieldsValues['label_' . $languageKey]	= (string) $translation->value;
				}
			}

				// Add URLs
			$url	= (string) $library->url;
			foreach($this->languageKeys as $key) {
				$fieldsValues['url_' . $key]	= $url;
			}

			try {
				$this->DB->execInsert($fieldsValues, $table);
			} catch(Exception $e) {
				throw new Exception( $e->getMessage() );
			}
		}
	}

	/**
	 * Validate XML file via DTD, create SimpleXMLElement from it
	 * Precondition: "markup.dtd" must exist in the same folder as the XML file
	 *
	 * @throws	Exception
	 * @param	String				$file
	 * @return	SimpleXMLElement	SimpleXMLElement Object from contents of given XML file
	 */
	private function initSimpleXmlFromFromFile($file) {
		try {
			$xmlString	= $this->getFileContents($file);
				// Ensure XML to be not empty
			if( empty($xmlString) ) {
				throw new Exception('Import file: "' . basename($file) . '" is empty!');
			}
				// Ensure XML to be valid - load and insert DTD
			$dtd	= $this->getFileContents( dirname($file) . DIRECTORY_SEPARATOR . 'markup.dtd');
			$xmlString	= str_replace('<libraryconfiguration>', $dtd . '<libraryconfiguration>', $xmlString);
				// Validate XML from DTD
			$dom = new DOMDocument;
			$dom->loadXML($xmlString);
			if( !$dom->validate() ) {
				throw new Exception('Invalid XML in file: "' . basename($file) . '".');
			}
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}

		return simplexml_load_string($xmlString);
	}

	/**
	 * @throws	Exception
	 * @param	String		$file
	 * @return	String		Contents of given file
	 */
	private function getFileContents($file) {
		if( !is_file($file) ) {
			throw new Exception('File not found: ' . $file);
		}

		return file_get_contents($file);
	}

	/**
	 * Connect to MySql database
	 *
	 * @throws	Exception
	 */
	public function connectToDatabase() {
		$dsn		= $this->dbConfig['dsn'];

        $dsnAttributes = explode(":",$dsn);

        $HostAndDB = array();

        $concatAttributes = null;
        foreach ($dsnAttributes as $element) {
            if (preg_match('/dbname/',strtolower($element)) && preg_match('/host/',strtolower($element)) ) {

                $singleAttributes = explode(";",$element);
                foreach ($singleAttributes as $attribute) {
                    $tAttribute = explode("=",$attribute);

                    $HostAndDB[$tAttribute[0]] = $tAttribute[1];
                }


            }
        }

        if (sizeof($HostAndDB) == 0) {
            throw (new Exception ("wrong or missing definition of dbName and / or host name"));
        }

        //this doesn't work with a hostname like sb-db4.swissbib.unibas.ch
		//$host		= substr($dsn, strpos($dsn, ';host=') + 6);
		//$database	= substr($dsn, strpos($dsn, 'dbname=') + 7, strlen($dsn) - (strpos($dsn, ';host=')) - strlen('mysql:') - 1);

		try {
			$this->DB	= new DB($HostAndDB["host"], $HostAndDB["dbname"], $this->dbConfig['username'], $this->dbConfig['password']);
		} catch(Exception $e) {
			throw(new Exception($e->getMessage()));
		}
	}



}

// ==============================================================================

/**
 * Database utility class
 */
class DB {
	/**
	 * @var	Resource	MySQL link identifier
	 */
	private $link;

	/**
	 * @var	String
	 */
	private $queryLog;



	/**
	 * Connect to database
	 *
	 * @throws	Exception
	 * @param	String		$host
	 * @param	String		$database
	 * @param	String		$username
	 * @param	String		$password
	 */
	public function __construct($host, $database, $username, $password) {
		$this->link = mysqli_connect($host, $username, $password);
		if( $this->isConnected() ) {
			if( ! mysqli_select_db($this->link, $database) ) {
				$errorMessage	= 'Selecting database failed';
				$this->prependToQueryLog($errorMessage . ' - host:' . $host . ', database: ' . $database);
				throw new Exception($errorMessage);
			}
		} else {
			$errorMessage	= 'MySQL connection failed.';
			$this->prependToQueryLog($errorMessage . ' - host:' . $host . ', database: ' . $database . ', user: ' . $username . ', password: ' . $password);
			throw new Exception($errorMessage);
		}
	}

	/**
	 * @return	Boolean		Database link setup correctly?
	 */
	private function isConnected() {
		return is_object(($this->link)) && get_class($this->link) === 'mysqli';
	}

	/**
	 * @throws	Exception
	 * @param	Array	$fieldsValues
	 * @param	String	$table
	 */
	public function execInsert(array $fieldsValues, $table) {
		try {
			$this->query( $this->buildInsertQuery($fieldsValues, $table) );

		} catch(Exception $e) {
			throw new Exception( $e->getMessage() );
		}
	}

	/**
	 * Build INSERT query
	 *
	 * @param	Array	$fieldsValues
	 * @param	String	$table
	 * @return	String
	 */
	private function buildInsertQuery(array $fieldsValues, $table) {
		$fieldsValues	= self::prepareValuesForInsert($fieldsValues);
		$table			= trim($table);

		$query	=
			'INSERT INTO `' . $table . '` ' . "\n"
		  . '			(' . implode(',', array_keys($fieldsValues)) . ') ' . "\n"
		  . ' VALUES	(' . implode(',', array_values($fieldsValues)) . '); ';

		return $query;
	}

	/**
	 * @throws	Exception
	 * @param	Array		$fieldsValues
	 * @return	Array
	 */
	private function prepareValuesForInsert(array $fieldsValues) {
		if( $this->isConnected() ) {
			foreach($fieldsValues as $key => $value) {
				$fieldsValues[$key]	= '\'' . mysqli_real_escape_string($this->link, trim($value)) . '\'';
			}
		} else {
			throw new Exception('Query failed: no database link');
		}

		return $fieldsValues;
	}

	/**
	 * Send MySql query and add it the log
	 *
	 * @throws	Exception
	 * @param	String			$query
	 * @return	Object|Boolean
	 */
	private function query($query) {
		if( $this->isConnected() ) {
			$query		= trim($query);
			$resource	= mysqli_query($this->link, $query);

			$this->prependToQueryLog($query);
			if( mysqli_errno($this->link) ) {
				$errorMessage	= 'MySQL query failed: ' . mysqli_error($this->link);
				$this->prependToQueryLog($errorMessage, false);
				throw new Exception($errorMessage);
			}
		} else {
			throw new Exception('Query failed: no database link');
		}

		return $resource;
	}

	/**
	 * Add given query to top of query log
	 *
	 * @param	String	$query
	 * @param	Boolean	$withTimestamp
	 */
	private function prependToQueryLog($query, $withTimestamp = true) {
		$this->queryLog =
			( $withTimestamp ? '---------------- ' . date('Y-m-d H:m:s') : '' )
		.	"\n" . $query
		.	"\n\n"
		.	$this->queryLog;
	}

	/**
	 * (Over)write query log file
	 */
	public function storeQueryLog() {
		// @todo	implement?
	}

}

// ==============================================================================

/**
 * Static utility class for data conversion / generation methods
 */
class dataHelper {

	/**
	 * Get ISIL from institution code
	 *
	 * @param	String	$institutionCode	Institution code
	 * @param	String	$countryCode
	 * @return	String
	 */
	public static function getISIL($institutionCode = '', $countryCode = 'ch') {
		/**
		 * ISIL = International Standard Identifier for Libraries and Related Organizations, ISO 15511
		 *
		 * Rules for valid institution code
		 *		* Containing A-Z, a-z, 0-9, special chars: -, /, :
		 * 		* Maximum length:	11 chars
		 *
		 * @see	http://de.wikipedia.org/wiki/ISO_15511#ISIL
		 */
		return strtoupper($countryCode) . '-' . $institutionCode;
	}

	/**
	 * @param	String	$zipCode
	 * @return	String
	 */
	public static function getCantonFromZip($zipCode) {
		//@todo		implement maybe later...
		return '';
	}
}
