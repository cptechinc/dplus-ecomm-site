<?php namespace Controllers\Abstracts;
// Base PHP
use ReflectionClass;
// Twig
use Twig\Environment as Twig;
use Twig\Loader\FilesystemLoader as TwigLoader;
// ProcessWire
use ProcessWire\Config as PwConfig;
use ProcessWire\Input;
use ProcessWire\Page;
use ProcessWire\Pages;
use ProcessWire\Session;
use ProcessWire\User;
use ProcessWire\WireData;
// App
use App\Configs\Configs\App as AppConfig;
use App\Ecomm\Services\Login as LoginService;
use App\Ecomm\Services\Login\Data\SessionUser as EcUser;
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
	const TITLE = '';
	const ALLOWED_PWROLES  = ['superuser', 'site-admin'];
	const LIMIT_ON_PAGE = 10;

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

	/**
	 * Check if user has necessesary ProcessWire Role
	 * @param  WireData|null $data
	 * @return bool
	 */
	protected static function validatePwRole(WireData $data = null) {
		if (empty(static::ALLOWED_PWROLES)) {
			return true;
		}
		$user = self::getPwUser();

		foreach (static::ALLOWED_PWROLES as $roleid) {
			if ($user->hasRole($roleid)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Return if Ordering is allowed Via config
	 * @param  WireData|null $data
	 * @return bool
	 */
	protected static function isOrderingAllowed(WireData $data = null) {
		/** @var AppConfig */
		$config = self::pw('config')->app;
		return $config->allowOrdering;
	}

	/**
	 * Check if ordering is allowed, if so redirect to home page
	 * @return bool
	 */
	protected static function initOrdering(WireData $data = null) {
		if (self::isOrderingAllowed() === false) {
			self::getPwSession()->redirect(self::pw('pages')->get('/')->url, $http301=false);
			return false;
		}
		return true;
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

	/**
	 * Return Twig Renderer
	 * @return TwigLoader
	 */
	protected static function getTwigLoader() {
		return self::pw('modules')->get('Twig')->loader;
	}

	/**
	 * Return ProcessWire Config
	 * @return PwConfig
	 */
	protected static function getPwConfig() {
		return self::pw('config');
	}

	/**
	 * Return ProcessWire Session
	 * @return Input
	 */
	protected static function getPwInput() {
		return self::pw('input');
	}

	/**
	 * Return ProcessWire Session
	 * @return Page
	 */
	protected static function getPwPage() {
		return self::pw('page');
	}

	/**
	 * Return ProcessWire Session
	 * @return Pages
	 */
	protected static function getPwPages() {
		return self::pw('pages');
	}

	/**
	 * Return ProcessWire Current User
	 * @return User
	 */
	protected static function getPwUser() {
		return self::pw('user');
	}

	/**
	 * Return ProcessWire Session
	 * @return Session
	 */
	protected static function getPwSession() {
		return self::pw('session');
	}

	/**
	 * Return Ecomm User Data
	 * @return EcUser
	 */
	protected static function getEcUser() {
		return self::getPwSession()->ecuser;
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
		return rtrim(static::getNamespaceAsPath(), '/') . '/' . static::getClassNameAsPath() . '/';
	}

	/**
	 * Append jQuery Validate scripts
	 * @return bool
	 */
	public static function appendJsJqueryValiudate() {
		$fh     = self::getFileHasher();
		$config = self::getPwConfig();

		$config->scripts->append($fh->getHashUrl('vendor/jquery.validate/jquery.validate.min.js'));
		$config->scripts->append($fh->getHashUrl('vendor/jquery.validate/additional-methods.min.js'));
		$config->scripts->append($fh->getHashUrl('scripts/jquery.validate-setup.js'));
		return true;
	}

	/**
	 * Add Script Paths to be listed in HTML
	 * @param  WireData $data
	 * @param  array    $scripts  Relative Script Paths
	 * @return void
	 */
	protected static function appendJs(WireData $data, $scripts = []) {
		$fh = self::getFileHasher();
		$config = self::getPwConfig();

		foreach ($scripts as $file) {
			if (file_exists($config->paths->templates . $file)) {
				$config->scripts->append($fh->getHashUrl($file));
			}
		}
	}

	/**
	 * Return Page number based off offset and show on Page
	 * @param  int      $offset
	 * @param  int|null $limit
	 * @return int
	 */
	public static function getPagenbrFromOffset(int $offset, int $limit = null) {
		if (empty($limit)) {
			$limit = 1;
		}
		$pagenbr = ceil($offset / $limit);
		return $pagenbr;
	}

	/**
	 * Return Offset based from Page Number
	 * @param  int      $offset
	 * @param  int $limit
	 * @return int
	 */
	public static function getOffsetFromPagenbr(int $pagenbr, int $limit = 0) {
		if (empty($limit)) {
			$limit = 1;
		}
		if ($pagenbr == 1) {
			return 0;
		}
		return ($pagenbr * $limit) - $limit;
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
