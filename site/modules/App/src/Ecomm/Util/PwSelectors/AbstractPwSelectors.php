<?php namespace App\Ecomm\Util\PwSelectors;
// ProcessWire
use ProcessWire\Sanitizer;
// Pauldro ProcessWire
use Pauldro\ProcessWire\AbstractStaticPwClass;

/**
 * AbstractPwSelectors
 * Template class for Generating Selector Strings
 */
class AbstractPwSelectors extends AbstractStaticPwClass {
	/**
	 * Return Selector String In Parentheses
	 * @param  string $selector
	 * @return string
	 */
	protected static function selectorInParens($selector) {
		return "($selector)";
	}

	/**
	 * Return Sanitizer
	 * @return Sanitizer
	 */
	protected static function pwSanitizer() {
		return self::pw('sanitizer');
	}
}