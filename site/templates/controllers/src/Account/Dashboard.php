<?php namespace Controllers\Account;
// ProcessWire
use ProcessWire\WireData;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Account as AccountController;

/**
 * Dashboard
 * Handles Dashboard Page Requests
 */
class Dashboard extends AbstractController {
	const SESSION_NS = 'dashboard';
	const REQUIRE_LOGIN = true;
	const TITLE = 'Your Dashboard';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		if (self::init() === false) {
			return false;
		}
		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);
		self::pw('page')->title = self::TITLE;
		
		self::initPageHooks();
		return self::display($data);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */

/* =============================================================
	4. URLs
============================================================= */
	public static function url() {
		return AccountController::url() . 'dashboard/';
	}

/* =============================================================
	5. Displays
============================================================= */
	protected static function display(WireData $data) {
		return static::render($data);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	protected static function render(WireData $data) {
		return self::getTwig()->render('account/dashboard/page.twig');
	}

/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	9. Hooks / Object Decorating
============================================================= */
	/**
	 * Initialze Page Hooks
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPageHooks($tplname = '') {
		$selector = static::getPageHooksTemplateSelector();
		$m = self::pw('modules')->get('App');

		$m->addHook("$selector::changePasswordUrl", function($event) {
			$event->return = AccountController\ChangePassword::url();
		});

		$m->addHook("$selector::ordersUrl", function($event) {
			$event->return = AccountController\Orders\Orders::url();
		});

		$m->addHook("$selector::historyUrl", function($event) {
			$event->return = AccountController\Orders\History::url();
		});
	}
}