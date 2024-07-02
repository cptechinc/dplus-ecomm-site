<?php namespace Controllers\Account;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Account\Password as Service;
// Controllers
use Controllers\Account as AccountController;


/**
 * FirstLogin
 * Handles FirstLogin Requests
 */
class FirstLogin extends AbstractServiceController {
	const SESSION_NS  = 'first-login';
	const PAGE_NAME   = 'first-login';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		if (static::init() === false) {
			return false;
		}
		$fields = ['action|text'];
		self::sanitizeParametersShort($data, $fields);

		if ($data->action) {
			return static::process($data);
		}
		static::appendJs($data);
		return static::display($data);
	}

	/**
	 * Process Action Request
	 * @param  WireData $data
	 * @return bool
	 */
	public static function process(WireData $data) {
		$fields = ['logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		$service = Service::instance();
		$success = $service->process($data);

		$url = self::url();

		if ($success) {
			$service->parseLoginIntoSession();
			$url = AccountController::url();
		}
		self::pw('session')->redirect($url, $http301=false);
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
	protected static function appendJs(WireData $data) {
		self::appendJsJqueryValiudate();
		$fh = self::getFileHasher();
		$config = self::getPwConfig();
		$jsPath = 'scripts/pages/' . self::getNamespaceClassNameAsPath();

		$scripts = ['form.js'];

		foreach ($scripts as $file) {
			if (file_exists($config->paths->templates . $jsPath . $file)) {
				$config->scripts->append($fh->getHashUrl($jsPath . $file));
			}
		}
	}
}