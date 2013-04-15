<?php

/**
 * Static utility class for data conversion / generation methods
 */
class DataHelper {

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
		$zipCode	= intval($zipCode);

		return '';
	}
}
