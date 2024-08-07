<?php namespace App\Pw\Hooks;
// ProcessWire
use ProcessWire\Session as PwSession;
// App
use App\Ecomm\Services\Login as LoginService;

/**
 * Session
 * Add hooks for Session
 */
class Session extends AbstractStaticHooksAdder {
/* =============================================================
	Hooks
============================================================= */	
	/**
	 * Add hooks to get URLs
	 * @return void
	 */
	public static function addHooks() {
		$m = self::pwModuleApp();

		$m->addHook("Session::isLoggedInEcomm", function($event) {
			/** @var PwSession */
			$user = $event->object;
			$event->return = LoginService::instance()->isLoggedIn();
		});
	}
}