<?php namespace Controllers\Account;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Account\Register as Service;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Account as AccountController;

/**
 * Register
 * Handles Register Requests
 */
class Register extends AbstractController {
	const SESSION_NS = 'register';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		if (self::init() === false) {
			return false;
		}
		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		if (Service::instance()->isLoggedIn()) {
			self::pw('session')->redirect(AccountController::url(), $http301=false);
			return false;
		}

		if ($data->logout || $data->action) {
			return self::process($data);
		}
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
	public static function url() {
		return AccountController::url() . 'register/';
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data) {
		return self::render($data);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data) {
		return self::getTwig()->render('account/register/page.twig');
	}

/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	8. Supplemental
============================================================= */
}