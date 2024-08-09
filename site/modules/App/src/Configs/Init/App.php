<?php namespace App\Configs\Init;
// App
use App\Configs\Configs;

/**
 * App
 * Appends / Updates App Config to Global ProcessWire Config
 * 
 * @method Configs\App new        Return new instance of Config
 * @method Configs\App default    Return new instance of Config with values hydrated
 * @method static self instance()  Return Instance
 */
class App extends AbstractConfigInitializer {
	const KEY = 'app';

	protected static $instance;

	public function new() {
		return new Configs\App();
	}

	/**
	 * Update config
	 * @param  Configs\App $config
	 * @return void
	 */
	protected function hydrateConfig(Configs\AbstractConfig $config) {
		$this->updateAllowOrdering($config);
	}

	/**
	 * Update Allow Ordering Value
	 * @param  Configs\App $config
	 * @return bool
	 */
	private function updateAllowOrdering(Configs\App $config) {
		if ($config->allowOrdering === false) {
			return false;
		}
		if ($this->user->isLoggedinEcomm() === false) {
			$config->allowOrdering = false;
			return false;
		}
		return true;
	}
}