<?php namespace App\Configs\Configs;
// ProcessWire
use ProcessWire\WireArray;

/**
 * Site
 * Container for Site Config
 * 
 * @property bool  $useTopbar  Use Topbar
 */
class Site extends AbstractConfig {
	public function __construct() {
		$this->useTopbar = true;
	}
}