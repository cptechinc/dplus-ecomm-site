<?php namespace App\Configs\Init;
// ProcessWire
use ProcessWire\Config;
use ProcessWire\WireData;
// App
use App\Configs\Configs;

/**
 * AbstractConfigInitializer
 * Appends / Updates Config to Global ProcessWire\Config
 * 
 */
abstract class AbstractConfigInitializer extends WireData {
	const KEY = 'conf';

	protected static $instance;

	/**
	 * Return Instance
	 * @return static
	 */
	public static function instance() {
		if (empty(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Return New instance of Config
	 * @return Configs\AbstractConfig
	 */
	public function new() {
		$c = new Configs\AbstractConfig();
		return $c;
	}

	/**
	 * Return new Config Hydrated with values
	 * @return Configs\AbstractConfig
	 */
	public function default() {
		$c = $this->new();
		$this->hydrateConfig($c);
		return $c;
	}

	/**
	 * Initializes Config
	 * @return bool
	 */
	public function init() {
		if ($this->config->has(static::KEY)) {
			$this->updateMissingProperties();
			$this->hydrateConfig($this->config->get(static::KEY));
			return true;
		}
		$config = $this->default();
		$this->config->set(static::KEY, $config);
	}

	/**
	 * Update config
	 * @param  Configs\AbstractConfig $config
	 * @return void
	 */
	protected function hydrateConfig(Configs\AbstractConfig $config) {
		
	}

	/**
	 * Add Config Properties that are missing
	 * @return bool
	 */
	protected function updateMissingProperties() {
		$default = $this->default();
		$config  = $this->config->get(static::KEY);

		foreach ($default as $key => $value) {
			if ($config->has($key)) {
				continue;
			}
			$config->set($key, $value);
		}
		return true;
	}
}