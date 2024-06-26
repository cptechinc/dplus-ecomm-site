<?php namespace Controllers;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Login as Service;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * Login
 * Handles Login Requests
 */
class Login extends AbstractController {
	const SESSION_NS = 'login';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		if ($data->logout || $data->action) {
			return self::process($data);
		}
		// if (Service::instance()->isLoggedIn()) {
		// 	$role = self::pw('roles')->get(self::pw('user')->dplusRole);
		// 	$url = $role->homepage;
		// 	if (empty($url)) {
		// 		$url = self::pw('pages')->get('/')->url;
		// 	} 
		// 	self::pw('session')->redirect($url, $http301=false);
		// }
		// return self::render();
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

		if ($data->logout) {
			$service->logout();
			self::deleteSessionVar('attempts');
			self::pw('session')->redirect(self::url(), $http301=false);
			return true;
		}
		$success = $service->process($data);

		if ($data->action != 'login') {
			self::pw('session')->redirect(self::url(), $http301=false);
			return true;
		}
		self::handleLogin($data);
	}

	/**
	 * Process Login Results
	 * @param  WireData $data
	 * @return bool
	 */
	private static function handleLogin(WireData $data) {
		$service = Service::instance();

		if ($service->isLoggedIn() === false) {
			return self::handleLoginError($data);
		}
		return self::handleLoginSuccess($data);
	}

	/**
	 * Process / Handle Login Error
	 * @param  WireData $data
	 * @return bool
	 */
	private static function handleLoginError(WireData $data) {
		$service = Service::instance();

		if ($service->exists() === false) {
			self::setSessionVar('no-response', 'no-response');
		}
		$attempts = intval(self::getSessionVar('attempts'));
		$attempts++;
		self::setSessionVar('attempts', $attempts);
		self::pw('session')->redirect(self::url(), $http301=false);
		return true;
	}

	/**
	 * Process / Handle Login Success
	 * @param  WireData $data
	 * @return bool
	 */
	private static function handleLoginSuccess(WireData $data) {
		self::deleteSessionVar('attempts');
		self::deleteSessionVar('no-response');
		$service = Service::instance();

		if ($service->parseLoginIntoSession() === false) {
			$attempts = intval(self::getSessionVar('attempts'));
			$attempts++;
			self::setSessionVar('attempts', $attempts);
			self::pw('session')->redirect(self::url(), $http301=false);
		}
		self::pw('session')->redirect(self::url(), $http301=false);
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
		return self::pw('pages')->get('template=login')->url;
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