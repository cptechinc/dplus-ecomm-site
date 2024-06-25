<?php namespace App\Css;
// ProcessWire
use ProcessWire\WireData;

/**
 * Classes
 * Container for Classes
 */
class Classes extends WireData {
	private static $instance;

	public static function instance() {
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		$this->icons = Classes\Icons::instance();

		$this->colors = new WireData(); 
		$this->colors->backgrounds = Classes\Colors\Backgrounds::instance();
		$this->colors->buttons = Classes\Colors\Buttons::instance();
	}

}