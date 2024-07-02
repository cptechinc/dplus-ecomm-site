<?php namespace Controllers\Account;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Account\Register as Service;
// Controllers
use Controllers\Account as AccountController;

/**
 * Register
 * Handles Register Requests
 */
class Register extends AbstractServiceController {
	const SESSION_NS = 'register';
	const PAGE_NAME  = 'register';
	const REQUIRE_LOGIN = false;
	const TITLE = 'Register for an Account';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		if (self::init() === false) {
			return false;
		}

		if (Service::instance()->isLoggedIn()) {
			self::pw('session')->redirect(AccountController::url(), $http301=false);
			return false;
		}

		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);
		
		if ($data->action) {
			return self::process($data);
		}
		self::pw('page')->title = self::TITLE;
		$html = self::display($data);
		self::deleteSessionVar('emailsent');
		return $html;
	}

	/**
	 * Handle Login / Logout
	 * @param  WireData $data
	 * @return void
	 */
	public static function process(WireData $data) {
		$fields = ['logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		$service = Service::instance();
		$success = $service->process($data);
		$url = self::url();

		if ($success) {
			self::setSessionVar('emailsent', $data->email);
		}
		self::pw('session')->redirect($url, $http301=false);
		return true;
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

/* =============================================================
	5. Displays
============================================================= */

/* =============================================================
	6. HTML Rendering
============================================================= */

/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	8. Supplemental
============================================================= */
}