<?php namespace App\Ecomm\Abstracts\Services;
// ProcessWire
use ProcessWire\WireData;

/**
 * AbstractService
 * Template
 */
abstract class AbstractService extends WireData {
	const SESSION_NS = '';

	protected static $instance;

	/** @return static */
	public static function instance() {
		if (empty(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}

/* =============================================================
	Sessions
============================================================= */
	/**
	 * Set Session Variable
	 * @param  string $key
	 * @param  string $value
	 * @return bool
	 */
	public function setSessionVar($key = '', $value) {
		return $this->session->setFor(static::SESSION_NS, $key, $value);
	}

	/**
	 * Return Session Variable
	 * @param  string $key
	 * @return mixed
	 */
	public function getSessionVar($key = '') {
		return $this->session->getFor(static::SESSION_NS, $key);
	}

	/**
	 * Delete Session Variable
	 * @param  string $key
	 * @return bool
	 */
	public function deleteSessionVar($key = '') {
		return $this->session->removeFor(static::SESSION_NS, $key);
	}
}