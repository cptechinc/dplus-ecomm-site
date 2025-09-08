<?php namespace Controllers\Account;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Account\Password as Service;
// Controllers
use Controllers\Account as AccountController;

/**
 * ForgotPassword
 * Handles ForgotPassword Requests
 */
class ForgotPassword extends AbstractServiceController {
	const REQUIRE_LOGIN = false;
	const SESSION_NS    = 'forgot-password';
	const PAGE_NAME     = 'forgot-password';
	const TITLE         = 'Recover Your Account';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		if (self::init() === false) {
			return false;
		}

		if (Service::instance()->isLoggedIn()) {
			self::pw('session')->redirect(AccountController\Dashboard::url(), $http301=false);
			return false;
		}

		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		if ($data->action) {
			return self::process($data);
		}
		self::pw('page')->title = self::TITLE;
		self::appendJs($data);
		$html = self::display($data);
		self::deleteSessionVar('emailsent');
		return $html;
	}

	/**
	 * Process Action Request
	 * @param  WireData $data
	 * @return bool
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
	/**
	 * Return if Login is required for this Controller
	 * @param  WireData|null $data
	 * @return bool
	 */
	public static function isLoginRequired(WireData $data = null) {
		return static::REQUIRE_LOGIN;
	}
	
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
	protected static function appendJs(WireData $data, $scripts = []) {
		self::appendJsJqueryValiudate();

		$scripts = self::getJsScriptPaths($data);
		parent::appendJs($data, $scripts);
	}
}