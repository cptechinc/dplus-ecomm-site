<?php namespace Controllers;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\ContactUs as Service;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * ContactUs
 * Handles Contact Us Page
 */
class ContactUs extends AbstractController {
	const SESSION_NS = 'contact-us';
	const TEMPLATE   = 'contact-us';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		self::initPageHooks();
		if (self::getSessionVar('emailed') === true) {
			return self::displayEmailSent($data);
		}
		return self::display($data);
	}

	public static function process(WireData $data) {
		if (self::init() === false) {
			return false;
		}
		$fields = ['action|text'];
		self::sanitizeParametersShort($data, $fields);

		$SERVICE = Service::instance();
		$data->success = $SERVICE->process($data);
		self::getPwSession()->redirect(self::url(), $http301=false);
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
		return self::pw('pages')->get('template=contact-us')->url;
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data) {
		return self::render($data);
	}

	private static function displayEmailSent(WireData $data) {
		return self::renderEmailSent($data);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data) {
		return self::getTwig()->render('contact-us/page.twig');
	}

	private static function renderEmailSent(WireData $data) {
		return self::getTwig()->render('contact-us/page-email-sent.twig');
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
		// $selector = static::getPageHooksTemplateSelector();
		// $m = self::pw('modules')->get('App');
	}

	/**
	 * Add Hooks to Pages
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPagesHooks() {
		// $m = self::pw('modules')->get('App');
	}
}