<?php namespace App\Configs\Init;
// App
use App\Configs\Configs;

/**
 * Register
 * Appends / Updates Register Config to Global ProcessWire Config
 * 
 * @method Configs\App new        Return new instance of Config
 * @method Configs\App default    Return new instance of Config with values hydrated
 * @method static self instance()  Return Instance
 */
class Register extends AbstractConfigInitializer {
	const KEY = 'register';

	protected static $instance;

	public function new() {
		return new Configs\Register();
	}
}