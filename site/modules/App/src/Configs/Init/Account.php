<?php namespace App\Configs\Init;
// App
use App\Configs\Configs;

/**
 * Account
 * Appends / Updates Account Config to Global ProcessWire Config
 * 
 * @method Configs\App new        Return new instance of Config
 * @method Configs\App default    Return new instance of Config with values hydrated
 * @method static self instance()  Return Instance
 */
class Account extends AbstractConfigInitializer {
	const KEY = 'account';

	protected static $instance;

	public function new() {
		return new Configs\Account();
	}
}