<?php namespace App\Configs\Configs;
// ProcessWire
use ProcessWire\WireArray;
// App
use App\Pw\Roles;

/**
 * App
 * Container for Application Config Data
 * 
 * @property bool $requireLogin  Require Login to view Site?
 */
class App extends AbstractConfig {
	public function __construct() {
		$this->requireLogin = false;
	}
}