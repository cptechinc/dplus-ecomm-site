<?php namespace App\Pw\Hooks;
// ProcessWire
use ProcessWire\App as PwApp;
// Pauldro ProcessWire
use Pauldro\ProcessWire\AbstractStaticPwClass;

/**
 * AbstractStaticHooksAdder
 * Template Class for Statically adding Hooks
 */
class AbstractStaticHooksAdder extends AbstractStaticPwClass {
	/**
	 * Return Module App
	 * @return PwApp
	 */
	protected static function pwModuleApp() {
		return self::pw('modules')->get('App');
	}
}