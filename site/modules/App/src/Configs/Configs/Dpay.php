<?php namespace App\Configs\Configs;
// ProcessWire
use ProcessWire\WireArray;
// App
use App\Pw\Roles;

/**
 * Dpay
 * Container for Dpay Config Data
 * 
 * @property bool $allowCreatePaymentLinks
 */
class Dpay extends AbstractConfig {
	public function __construct() {
		$this->allowCreatePaymentLinks = true;
	}
}