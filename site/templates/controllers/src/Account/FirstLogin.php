<?php namespace Controllers\Account;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Account\Password as Service;
use App\Ecomm\Services\Login as LoginService;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Account as AccountController;
use Controllers\Login as LoginController;


/**
 * Login
 * Handles Login Requests
 */
class FirstLogin extends AbstractController {
	const SESSION_NS = 'first-login';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		if (Service::instance()->isLoggedIn() === false) {
			self::pw('session')->redirect(LoginController::url(), $http301=false);
			return false;
		}

		if ($data->logout || $data->action) {
			return self::process($data);
		}
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
			$service->parseLoginIntoSession();
			$url = AccountController::url();
		}

		self::pw('session')->redirect($url, $http301=false);
		return false;
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
		return self::pw('pages')->get('template=account')->url . 'first-login/';
	}

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