<?php namespace  Dplus\Database\Tables;
// ProcessWire
use ProcessWire\WireData;
// Dplus
	use Dplus\Abstracts\AbstractQueryWrapper;

/**
 * Factory
 * Fetches instances of Database Tables
 * 
 * @static self $instance
 */
class Factory extends WireData {
	const KEYMAP = [
		'sales-orders'  => 'SalesOrder',
		'sales-history' => 'SalesHistory',
	];
	private static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	/**  @return self */
	public static function instance() {
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return if Table code exists
	 * @param  string $code
	 * @return bool
	 */
	public function existsMap($code) {
		return array_key_exists($code, self::KEYMAP);
	}

	/**
	 * Return Class Name
	 * @param  string $code
	 * @return string
	 */
	public function getClass($code) {
		if ($this->existsMap($code) === false) {
			return false;	
		}
		return '\\' . __NAMESPACE__ . '\\' . self::KEYMAP[$code];
	}

	/**
	 * Return if Table Exists
	 * @param  string $code
	 * @return bool
	 */
	public function exists($code) {
		$class = $this->getClass($code);

		if ($class === false) {
			return false;
		}
		return class_exists($class);
	}

	/**
	 * Return Table
	 * @param  string $code
	 * @return AbstractQueryWrapper|false
	 */
	public function fetch($code) {
		if ($this->exists($code) === false) {
			return false;
		}
		$class = $this->getClass($code);
		return $class::instance();
	}
}