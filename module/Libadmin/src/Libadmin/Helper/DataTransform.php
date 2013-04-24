<?php
namespace Libadmin\Helper;


/**
 * Data transformation helpers
 */
class DataTransform
{

	/**
	 * Initialize
	 */
	public function __construct()
	{

	}



	/**
	 * Implode comma-separated list of numeric values to an array of integers
	 *
	 * @param    String		$numericStrings
	 * @param    Boolean	$unique
	 * @return   Integer[]
	 */
	public static function intExplode($numericStrings, $unique = true)
	{
		$numericStrings	= trim($numericStrings);
		$integers		= array();

		if (empty($numericStrings)) {
			return $integers;
		}

		$numericStrings	= explode(',', $numericStrings);
		foreach ($numericStrings as $numericString) {
			if (is_numeric($numericString)) {
				$integers[]	= intval($numericString);
			}
		}

		return $unique ? array_unique($integers) : $integers;
	}

}
