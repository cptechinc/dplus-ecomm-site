<?php namespace Controllers;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Login as Service;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Account as AccountController;

/**
 * Login
 * Handles Login Requests
 */
class Login extends AbstractController {
	const SESSION_NS = 'login';
	const TEMPLATE = 'login';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		if ($data->logout || $data->action) {
			return self::process($data);
		}
		if (Service::instance()->isLoggedIn()) {
			self::pw('session')->redirect(AccountController\Dashboard::url(), $http301=false);
			return false;
		}
		self::initPageHooks();
		return self::display($data);
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

		if ($service->isFirstLogin()) {
			self::pw('session')->redirect(Account\FirstLogin::url(), $http301=false);
			return true;
		}

		if ($service->parseLoginIntoSession() === false) {
			$attempts = intval(self::getSessionVar('attempts'));
			$attempts++;
			self::setSessionVar('attempts', $attempts);
			self::pw('session')->redirect(self::url(), $http301=false);
			return true;
		}
		self::pw('session')->redirect(Account\Dashboard::url(), $http301=false);
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
		return self::pw('pages')->get('template=login')->url;
	}

	public static function logoutUrl() {
		return self::url() . '?' . http_build_query(['logout' => 'true']);
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
		return self::getTwig()->render('login/page.twig');
	}

/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	8. Supplemental
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

		$m->addHook("$selector::forgotPasswordUrl", function($event) {
			$event->return = AccountController\ForgotPassword::url();
		});

		$m->addHook("$selector::registerUrl", function($event) {
			$event->return = AccountController\Register::url();
		});
	}

	/**
	 * Add Hooks to Pages
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPagesHooks() {
		$m = self::pw('modules')->get('App');

		$m->addHook("Pages::logoutUrl", function($event) {
			$event->return = self::logoutUrl($event->arguments(0));
		});
	}
}