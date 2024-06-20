<?php namespace Pauldro\Util\Regex;

/**
 * Phone
 * Utility class for Generating phone output with Regex
 */
class Phone {
	const REGEXES = [
		'us' => [
			'10' => '(\d{3})(\d{3})(\d{4})',
		]
	];

	const REGEX_OUTPUTS = [
		'us' => [
			'10'  => '$1-$2-$3',
			'ext' => ' X $4'
		]
	];

	public static function us_10($phone) {
		$numbers_only = preg_replace("/[^\d]/", "", $phone);
		$regex  = self::REGEXES['us']['10'];
		$output = self::REGEX_OUTPUTS['us']['10'];
		return preg_replace("/^1?".$regex."$/", $output, $numbers_only);
	}

	public static function us_x($phone) {
		$numbers_only = preg_replace("/[^\d]/", "", $phone);
		$regex  = self::REGEXES['us']['10'];
		$output = self::REGEX_OUTPUTS['us']['10'];
		$phonelength = strlen($numbers_only);

		if ($phonelength > 10) {
			$extlength = $phonelength - 10;
			$regex  .='(\d{'.$extlength.'})';
			$output .= self::REGEX_OUTPUTS['us']['ext'];
		}
		return preg_replace("/^1?".$regex."$/", $output, $numbers_only);
	}

	public static function us_ext($phone) {
		$numbers_only = preg_replace("/[^\d]/", "", $phone);
		$phonelength = strlen($numbers_only);

		if (strlen($phonelength) > 10) {
			return substr($numbers_only, 10);
		}
		return $numbers_only;
	}
}