<?php namespace Controllers\Abstracts;
// Base PHP
use ReflectionClass;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Login as LoginService;
// Mvc Controllers
use Mvc\Controllers\AbstractController as ParentController;
// Controllers
use Controllers\Login as LoginController;

/**
 * AbstractController
 */
abstract class AbstractController extends ParentController {
	const SESSION_NS = '';
	const REQUIRE_LOGIN = false;

/* =============================================================
	1. Indexes
============================================================= */

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

	/**
	 * Init
	 * @return bool
	 */
	protected static function init(WireData $data = null) {
		return static::initLogin($data);
	}

	/**
	 * Check if Login is required, if so redirect to login page
	 * @return bool
	 */
	protected static function initLogin(WireData $data = null) {
		if (static::isLoginRequired() === false) {
			return true;
		}
		if (LoginService::instance()->isLoggedIn()) {
			return true;
		}
		LoginController::setSessionVar('redirectUrl', self::pw('input')->url(['withQueryString' => true]));
		self::pw('session')->redirect(LoginController::url(), $http301=false);
		return false;
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
	/**
	 * Return Reflection Class
	 * @return ReflectionClass
	 */
	protected static function getStaticReflectionClass() {
		return new ReflectionClass(static::class);
	}

	/**
	 * Return Namespace as path
	 * @return string
	 */
	protected static function getNamespaceAsPath() {
		$insp = static::getStaticReflectionClass();
		$ns = $insp->getNamespaceName();
		$ns = preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $ns);
		$ns = strtolower($ns);
		$ns = ltrim($ns, 'controllers\\');
		$ns = str_replace("\\", '/', $ns);
		return $ns;
	}

	/**
	 * Return Class Name as path
	 * @return string
	 */
	protected static function getClassNameAsPath() {
		$insp = static::getStaticReflectionClass();
		$class = $insp->getShortName();
		$class = preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $class);
		$class = strtolower($class);
		return $class;
	}

	/**
	 * Return Namespace + Class Name as Path
	 * @return string
	 */
	protected static function getNamespaceClassNameAsPath() {
		return ltrim(static::getNamespaceAsPath() . '/' . static::getClassNameAsPath(), '/');
	}

/* =============================================================
	9. Hooks / Object Decorating
============================================================= */

/* =============================================================
	10. Sessions
============================================================= */
	/**
	 * Set Session Variable
	 * @param  string $key
	 * @param  string $value
	 * @return bool
	 */
	public static function setSessionVar($key = '', $value) {
		return self::pw('session')->setFor(static::SESSION_NS, $key, $value);
	}

	/**
	 * Return Session Variable
	 * @param  string $key
	 * @return mixed
	 */
	public static function getSessionVar($key = '') {
		return self::pw('session')->getFor(static::SESSION_NS, $key);
	}

	/**
	 * Delete Session Variable
	 * @param  string $key
	 * @return bool
	 */
	public static function deleteSessionVar($key = '') {
		return self::pw('session')->removeFor(static::SESSION_NS, $key);
	}

/* =============================================================
	11.Redirects
============================================================= */
	/**
	 * Redirect to Login Page
	 * @return bool
	 */
	protected static function redirectToLogin() {
		self::pw('session')->redirect(LoginController::url(), $http301=false);
		return true;
	}
}
