<?php namespace Pauldro\ProcessWire;
// ProcessWire
use ProcessWire\WireData as AbstractWireData;

/**
 * WireData
 * WireData class that adds functionality
 */
class WireData extends AbstractWireData {
/* =============================================================
	2. Getters
============================================================= */
	/**
	 * Return Array
	 * @return array
	 */
	public function toArray() {
		return $this->data;
	}

/* =============================================================
	2a. Setters
============================================================= */
	/**
	 * Provides direct reference access to set values in the $data array
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function __set($key, $value) {
		$method = "set".ucfirst($key);
		
		if (method_exists($this, $method)) {
			return $this->$method($value);
		}
		return $this->set($key, $value); 
	}
}