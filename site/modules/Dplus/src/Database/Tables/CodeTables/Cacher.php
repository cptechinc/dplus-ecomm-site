<?php namespace  Dplus\Database\Tables\CodeTables;
// ProcessWire
use ProcessWire\WireData;
// Pauldro ProcessWire
use Pauldro\ProcessWire\WireCache;

/**
 * Cacher
 * 
 * @property WireCache $cacher
 */
class Cacher extends WireData {
	const EXPIRE = WireCache::expireWeekly;

	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	/** @return self */
	public static function instance() {
		if (empty(static::$instance) === false) {
			return static::$instance;
		}
		static::$instance = new static();
		return static::$instance;
	}

	public function __construct() {
		$this->cacher = WireCache::instance();
	}

/* =============================================================
	Reads
============================================================= */
	/**
	 * Return if Code Exists In Cache
	 * @param  string $table
	 * @param  string $code
	 * @return bool
	 */
	public function exists($table, $code) {
		return $this->cacher->existsFor($table, $code);
	}

	/**
	 * Return Cache Data for code
	 * @param  string $table
	 * @param  string $code
	 * @return array|null
	 */
	public function fetch($table, $code) {
		return $this->cacher->getFor($table, $code);
	}

/* =============================================================
	Updates
============================================================= */
	/**
	 * Save Code Cache
	 * @param  string $table
	 * @param  string $code
	 * @return bool
	 */
	public function save($table, $code, $data = null) {
		return $this->cacher->saveFor($table, $code, $data, static::EXPIRE);
	}

/* =============================================================
	Deletes
============================================================= */
	/**
	 * Delete Cache
	 * @param  string $table
	 * @param  string $code
	 * @return bool
	 */
	public function delete($table, $code) {
		return $this->cacher->deleteFor($table, $code);
	}
}