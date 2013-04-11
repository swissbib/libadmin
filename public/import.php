<?php
	// No warnings please
error_reporting(E_ERROR);
	// Start looking for files from root dir
chdir(dirname(__DIR__));

	// Take database credentials from ZF2 local config
$zf2LocalConf	= include('config/autoload/local.php');
try {
	$importer	= new LibadminXmlImporter($zf2LocalConf['db']);
	$xmlFile	= 'module/Libadmin/data/tpgreen-libraries.xml';

	echo $importer->import('module/Libadmin/data/tpgreen-libraries.xml', true);

} catch(Exception $e) {
	die('IMPORT FAILED - ' . $e->getMessage() );
}



// ==============================================================================

/**
 * Libadmin Importer
 * Imports data from XML file into Database
 */
class LibadminXmlImporter {

	/**
	 * @var	Array
	 */
	private $__dbConfig;

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

		$this->__dbConfig	= $dbConfig;
	}

	/**
	 * @throws	Exception
	 * @param	String		$file			XML file to be imported
	 * @param	Boolean		$validateDTD	Validate XML from DTD? (DTD must be in same folder as XML)
	 * @return	String
	 */
	public function import($file, $validateDTD = false) {
		try {
			$this->connectToDatabase();

			/** @var $xml SimpleXMLElement */
			$xml	= $this->initSimpleXmlFromFromFile($file, $validateDTD);

//@todo parse XML and insert the data into DB...

		} catch(Exception $e) {
			throw new Exception( $e->getMessage() );
		}

		return 'Data from "' . basename($file) . '" has been successfully imported.';
	}

	/**
	 * @throws	Exception
	 * @param	String				$file
	 * @param	Boolean				$validate	Use libadmin.dtd from same location as XML to validate?
	 * @return	SimpleXMLElement				SimpleXMLElement Object from contents of given XML file
	 */
	private function initSimpleXmlFromFromFile($file, $validate = false) {
			// Load XML file contents
		try {
			$xmlString	= $this->getFileContents($file);
				// Ensure XML to be not empty
			if( empty($xmlString) ) {
				throw new Exception('Import file: "' . basename($file) . '" is empty!');
			}
				// Optional: ensure XML to be valid
			if( $validate ) {
					// Load and insert DTD
				$dtd	= $this->getFileContents( dirname($file) . DIRECTORY_SEPARATOR . 'libadmin.dtd');
				$xmlString	= str_replace('<libraryconfiguration>', $dtd . '<libraryconfiguration>', $xmlString);

					// Validate XML from DTD
				$dom = new DOMDocument;
				$dom->loadXML($xmlString);
				if( !$dom->validate() ) {
					throw new Exception('Invalid XML in file: "' . basename($file) . '".');
				}
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
		$dsn		= $this->__dbConfig['dsn'];
		$server		= substr($dsn, strpos($dsn, ';host=') + 6);
		$database	= substr($dsn, strpos($dsn, 'dbname=') + 7, strlen($dsn) - (strpos($dsn, ';host=')) - strlen('mysql:') - 1);

		try {
			$this->db	= new DB($server, $database, $this->__dbConfig['username'], $this->__dbConfig['password']);
		} catch(Exception $e) {
			throw(new Exception($e->getMessage()));
		}
	}
}

// ==============================================================================

/**
 * Database query helper class
 */
class DB {
	/**
	 * @var	Resource	MySQL link identifier
	 */
	protected $link;

	/**
	 * Connect to database
	 *
	 * @throws	Exception
	 * @param	String		$server
	 * @param	String		$database
	 * @param	String		$username
	 * @param	String		$password
	 */
	public function __construct($server, $database, $username, $password) {
		$this->link = mysql_connect($server, $username, $password);
		if( $this->link ) {
			if( ! mysql_select_db($database, $this->link) ) {
				throw new Exception('Selecting database failed');
			}
		} else {
			throw new Exception('MySQL connection failed');
		}
	}

}
