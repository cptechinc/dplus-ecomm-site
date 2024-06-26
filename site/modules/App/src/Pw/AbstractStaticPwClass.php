<?php namespace App\Pw;
// ProcessWire
use ProcessWire\ProcessWire;

/**
 * AbstractStaticPwClass
 */
abstract class AbstractStaticPwClass {
	protected static $pw;

/* =============================================================
	Supplemental
============================================================= */	
	/**
	 * Return the current ProcessWire Wire Instance
	 * @param  string            $var   Wire Object
	 * @return ProcessWire|mixed
	 */
	protected static function pw($var = '') {
		if (empty(self::$pw)) {
			self::$pw = ProcessWire::getCurrentInstance();
		}
		return $var ? self::$pw->wire($var) : self::$pw;
	}
}