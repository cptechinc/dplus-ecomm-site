<?php namespace App\Pw\Hooks;
// ProcessWire
use ProcessWire\User as PwUser;
	// use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\Customer as CustomerTable;
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

		$m->addHookProperty("User::customer", function($event) {
			/** @var PwUser */
			$user = $event->object;

			if ($user->has('aCustomer')) {
				$event->return = $user->aCustomer;
				return true;
			}

			if ($user->isLoggedInEcomm() === false) {
				return false;
			}
			/** @var LoginService\Data\SessionUser */
			$session = $user->wire('session')->get('ecuser');
			$customer = CustomerTable::instance()->findOne($session->custid);

			if (empty($customer)) {
				return false;
			}
			$user->aCustomer = $customer;
			$event->return = $user->aCustomer;
			return true;
		});
	}
}