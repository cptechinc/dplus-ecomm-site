<?php namespace App\Pw\Hooks;
// ProcessWire
use ProcessWire\Session as PwSession;
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Login as LoginService;

/**
 * Session
 * Add hooks for Session
 * 
 * @static self $instance
 */
class Session extends WireData {
	private static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	/** @return self */
	public static function instance() {
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

/* =============================================================
	Hooks
============================================================= */	
	/**
	 * Add hooks to get URLs
	 * @return void
	 */
	public function addHooks() {
		$this->addHook("Session::isLoggedInEcomm", function($event) {
			/** @var PwSession */
			$user = $event->object;
			$event->return = LoginService::instance()->isLoggedIn();
		});
	}
}