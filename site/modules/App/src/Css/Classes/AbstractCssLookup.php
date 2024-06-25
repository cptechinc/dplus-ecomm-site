<?php namespace App\Css\Classes;
// ProcessWire
use ProcessWire\WireData;

/**
 * Icons
 * 
 * Handles Lookup of CSS icon classes
 */
abstract class AbstractCssLookup extends WireData {
	const MAP = [
		'add'       => 'fa fa-plus',
	];

	protected static $instance;

	public static function instance() {
		if (empty(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Return if Class exists for Code / Key
	 * @param  string $key
	 * @return bool
	 */
	public function hasClass($key) {
		return array_key_exists(strtolower($key), static::MAP);
	}

	/**
	 * Return CSS class if it exists by Key / code 
	 * @param  string $key
	 * @return string
	 */
	public function getClass($key) {
		if ($this->hasClass($key) === false) {
			return '';
		}
		return static::MAP[strtolower($key)];
	}
}