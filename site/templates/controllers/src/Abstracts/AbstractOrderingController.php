<?php namespace Controllers\Abstracts;
// Twig
use Twig\Environment as Twig;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Configs\Configs\App as AppConfig;

/**
 * AbstractOrderingController
 */
abstract class AbstractOrderingController extends AbstractController {
	const SESSION_NS = '';
	const REQUIRE_LOGIN = false;
	const TEMPLATE = '';
	const TITLE = '';
	const ALLOWED_PWROLES  = [];

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
		if (parent::isLoginRequired($data)) {
			return true;
		}
		/** @var AppConfig */
		$config = self::pw('config')->app;
		return $config->requireLoginToOrder;
	}

	/**
	 * Init
	 * @return bool
	 */
	protected static function init(WireData $data = null) {
		if (self::initOrdering() === false || self::initLogin() === false) {
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

/* =============================================================
	8. Supplemental
============================================================= */

/* =============================================================
	9. Hooks / Object Decorating
============================================================= */

/* =============================================================
	10. Sessions
============================================================= */

/* =============================================================
	11. Redirects
============================================================= */
}
