<?php namespace App\Pw\Hooks;
// ProcessWire
use ProcessWire\User as PwUser;
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Login as LoginService;

/**
 * User
 * Add hooks for User
 */
class User extends AbstractStaticHooksAdder {

/* =============================================================
	Hooks
============================================================= */	
	/**
	 * Add hooks to get URLs
	 * @return void
	 */
	public static function addHooks() {
		$m = self::pwModuleApp();

		$m->addHook("User::isLoggedInEcomm", function($event) {
			/** @var PwUser */
			$user = $event->object;
			$event->return = LoginService::instance()->isLoggedIn();
		});
	}
}