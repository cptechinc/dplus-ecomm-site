<?php namespace Pauldro\ProcessWire\Caches;
// ProcessWire
use ProcessWire\WireData;
// Pauldro ProcessWire
use Pauldro\ProcessWire\WireCache;

/**
 * AbstractCacheWireCache
 * Base Wrapper for caching data using WireCache
 * 
 * @property string $namespace  Cache Namespace
 */
abstract class AbstractCacheWireCache extends WireData {
	const EXPIRE = WireCache::expireDaily;
	const NAMESPACE = '';
	const ID_DELIMITER = '|';

	protected static $instance;

	public static function instance() {
		if (empty(static::$instance) === false) {
			return static::$instance;
		}
		static::$instance = new static();
		return static::$instance;
	}

	public function __construct() {
		$this->cacher = WireCache::instance();
		$this->namespace = static::NAMESPACE;
	}

	/**
	 * Return if ID exists in cache
	 * @param  string $id
	 * @return bool
	 */
	public function exists($id) {
		return $this->cacher->existsFor($this->namespace, $id);
	}

	/**
	 * Return Cache
	 * @param  string $id Cache ID
	 * @return mixed
	 */
	public function fetch($id) {
		return $this->cacher->getFor($this->namespace, $id);
	}

	/**
	 * Save Cache
	 * @param  string $id   Cache ID
	 * @param  mixed  $data
	 * @return bool
	 */
	public function save($id, $data = null) {
		return $this->cacher->saveFor($this->namespace, $id, $data, static::EXPIRE);
	}

	/**
	 * Delete Cache
	 * @param  string $id Cache ID
	 * @return bool
	 */
	public function delete($id) {
		return $this->cacher->deleteFor($this->namespace, $id);
	}
}