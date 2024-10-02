<?php namespace App\Configs\Init;
// App
use App\Configs\Configs;

/**
 * Dpay
 * Appends / Updates Dpay Config to Global ProcessWire Config
 * 
 * @method Configs\App new         Return new instance of Config
 * @method Configs\App default     Return new instance of Config with values hydrated
 * @method static self instance()  Return Instance
 */
class Dpay extends AbstractConfigInitializer {
	const KEY = 'dpay';

	protected static $instance;

	public function new() {
		return new Configs\Dpay();
	}
}