<?php namespace Controllers\Account;
// ProcessWire
use ProcessWire\WireData;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Account as AccountController;

/**
 * Dashboard
 * Handles Dashboard Page Requests
 */
class Dashboard extends AbstractController {
	const SESSION_NS = 'dashboard';
	const REQUIRE_LOGIN = true;

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		if (self::init() === false) {
			return false;
		}
		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);
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
		return AccountController::url() . 'dashboard/';
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