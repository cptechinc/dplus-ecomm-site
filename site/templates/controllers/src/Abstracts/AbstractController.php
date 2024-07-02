<?php namespace Controllers\Abstracts;
// Base PHP
use ReflectionClass;
// Twig
use Twig\Environment as Twig;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Login as LoginService;
use App\Configs\Configs\App as AppConfig;
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
	const TEMPLATE = '';

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
		/** @var AppConfig */
		$config = self::pw('config')->app;
		if ($config->requireLogin) {
			return $config->requireLogin;
		}
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
	/**
	 * Return Twig Renderer
	 * @return Twig
	 */
	protected static function getTwig() {
		return self::pw('modules')->get('Twig')->twig;
	}

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
	/**
	 * Initialize Hooks for Page
	 * @param  string $tplname Template Name
	 * @return void
	 */
	public static function initHooks($tplname = '') {
		static::initPageHooks();
	}

	/**
	 * Initialze Page Hooks
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPageHooks($tplname = '') {
		$m = self::pw('modules')->get('App');

		$selector = static::getPageHooksTemplateSelector();

		// $m->addHook("$selector::deleteItemUrl", function($event) {
		// 	$event->return = self::deleteItemUrl($event->arguments(0));
		// });
	}

	/**
	 * Return Selector for Page Hooks
	 * @param  string $tplname
	 * @return string
	 */
	public static function getPageHooksTemplateSelector($tplname = '') {
		$selector = 'Page';

		if ($tplname != '' || static::TEMPLATE != '') {
			if ($tplname == '') {
				$tplname = static::TEMPLATE;
			}
			$selector .= "(template=$tplname)";
		}
		return $selector;
	}

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
	11. Redirects
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
