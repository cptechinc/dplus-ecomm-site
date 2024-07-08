<?php namespace Controllers\Account;
// ProcessWire
use ProcessWire\WireData;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Account as AccountController;

/**
 * AbstractServiceController
 * Template for handling Account Service Requests
 */
abstract class AbstractServiceController extends AbstractController {
	const SESSION_NS    = 'account';
	const REQUIRE_LOGIN = true;
	const PAGE_NAME     = 'account';

/* =============================================================
	1. Indexes
============================================================= */
	/**
	 * Process HTTP GET Request
	 * @param  WireData $data
	 * @return string|bool
	 */
	public static function index(WireData $data) {
		if (static::init() === false) {
			return false;
		}
		$fields = ['action|text'];
		self::sanitizeParametersShort($data, $fields);

		if ($data->action) {
			return static::process($data);
		}
		self::pw('page')->title = static::TITLE;
		static::appendJs($data);
		return static::display($data);
	}


	/**
	 * Process Action Request
	 * @param  WireData $data
	 * @return bool
	 */
	abstract public static function process(WireData $data);

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
		return AccountController::url() . static::PAGE_NAME . '/';
	}

/* =============================================================
	5. Displays
============================================================= */
	protected static function display(WireData $data) {
		return static::render($data);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	protected static function render(WireData $data) {
		return self::getTwig()->render('account/' . static::PAGE_NAME . '/page.twig');
	}

/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	8. Supplemental
============================================================= */
}