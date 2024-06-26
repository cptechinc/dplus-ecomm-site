<?php namespace Controllers;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Login as LoginService;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * Account
 * Handles Account Page Requests
 */
class Account extends AbstractController {
	const SESSION_NS = 'account';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		if (LoginService::instance()->isLoggedIn() === false) {
			self::pw('session')->redirect(Login::url(), $http301=false);
		}
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
		return self::pw('pages')->get('template=account')->url;
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