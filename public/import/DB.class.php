<?php

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
	public function query($query) {
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
