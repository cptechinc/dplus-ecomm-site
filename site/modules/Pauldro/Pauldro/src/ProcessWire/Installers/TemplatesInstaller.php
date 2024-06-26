<?php namespace App\Ecomm\Pages;
// ProcessWire
use ProcessWire\WireData;


/**
 * TemplatesInstaller
 * Installs Templates
 * 
 * @property bool  $installOnlyNew
 * @property array $installed 
 */
class TemplatesInstaller extends WireData {
	const TEMPLATE_CLASSES = [
		
	];

	public function __construct() {
		$this->installOnlyNew = true;
	}

	/**
	 * Install Templates
	 * @return bool
	 */
	public function install() {
		$installed = [];
		foreach(static::TEMPLATE_CLASSES as $name => $classname) {
			$installed[$name] = false;

			/** @var AbstractTemplate $classname */
			if ($this->installOnlyNew === true && $classname::exists()) {
				$installed[$name] = true;
				continue;
			}
			$installed[$name] = $classname::install();
		}
		$this->installed = $installed;
		return true;
	}

	/**
	 * Install / Update Templates
	 * @return bool
	 */
	public function update() {
		$installed = [];
		
		foreach(static::TEMPLATE_CLASSES as $name => $classname) {
			$installed[$name] = $classname::install();
		}
		$this->installed = $installed;
		return true;
	}
}