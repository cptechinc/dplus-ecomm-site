<?php namespace Controllers\Admin\Pages;
// ProcessWire
use ProcessWire\WireData;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Admin as AdminController;

/**
 * AdminSite
 * Handles AdminSite Pages
 */
class Menu extends AbstractController {
	const SESSION_NS = 'admin';
	const TEMPLATE = 'admin-site';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		self::initPageHooks();
		return self::display($data);
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
	public static function url(){
		return AdminController::url() . 'pages/';
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data) {
		return self::render($data);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data) {
		return self::getTwig()->render('admin/pages/menu/page.twig');
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
	 * @return void
	 */
	public static function initPageHooks($tplname = '') {
		$selector = static::getPageHooksTemplateSelector();
		$m = self::pw('modules')->get('App');

		$m->addHook("$selector::productItemGroupsUrl", function($event) {
			$event->return = ProductsItemGroups::url();
		});

		$m->addHook("$selector::productsUrl", function($event) {
			$event->return = Products::url();
		});
	}
}