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
	 * Handle Display
	 * @param  WireData $data
	 * @return string
	 */
	abstract public static function index(WireData $data);


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