<?php namespace App\Pw\Hooks;
// ProcessWire
use ProcessWire\User as PwUser;
use ProcessWire\WireData;
// App
use App\User\LastPrintJob;
use App\User\CustomerAccess;

/**
 * User
 * Add hooks for User
 * 
 * @static self $instance
 */
class User extends WireData {
	private static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
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
		// $this->addHook("User::lastPrintJob", function($event) {
		// 	/** @var PwUser */
		// 	$user = $event->object;
		// 	$event->return = LastPrintJob::instance()->fetch($event->arguments(0));
		// });
	}
}