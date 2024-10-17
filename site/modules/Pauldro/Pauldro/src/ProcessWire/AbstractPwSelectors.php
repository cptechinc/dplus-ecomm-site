<?php namespace Pauldro\ProcessWire;
// ProcessWire
use ProcessWire\Sanitizer;

/**
 * AbstractPwSelectors
 * Template class for Generating Selector Strings
 */
class AbstractPwSelectors extends AbstractStaticPwClass {
	/**
	 * Return Sanitizer
	 * @return Sanitizer
	 */
	protected static function pwSanitizer() {
		return self::pw('sanitizer');
	}

	/**
	 * Return snippet for pagination
	 * @param  int    $page     Page to Start on
	 * @param  int    $limit    # of Results to Return
	 * @return string
	 */
	public static function pagination(int $page = 1, int $limit = 15) {
		$start = 0;
		if ($page > 1) {
			$start = $page * $limit - $limit;
		}
		return "start=$start,limit=$limit";
	}

	/**
	 * Return snippet for sort
	 * @param  string $fieldsort
	 * @return string
	 */
	public static function sort($fieldsort) {
		return "sort=$fieldsort";
	}
}