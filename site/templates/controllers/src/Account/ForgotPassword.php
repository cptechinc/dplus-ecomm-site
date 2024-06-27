<?php namespace Controllers\Account;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Account\Password as Service;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Account as AccountController;

/**
 * ForgotPassword
 * Handles ForgotPassword Requests
 */
class ForgotPassword extends AbstractController {
	const SESSION_NS = 'forgot-password';

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
		return AccountController::url() . 'forgot-password/';
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
		return self::getTwig()->render('account/forgot-password/page.twig');
	}


/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	8. Supplemental
============================================================= */
}