<?php namespace Controllers;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Cart as Service;
// Controllers
use Controllers\Abstracts\AbstractController;
use ProcessWire\HookEvent;
use Propel\Runtime\Collection\ObjectCollection;

/**
 * Cart
 * Handles Cart Page
 */
class Cart extends AbstractController {
	const SESSION_NS = 'cart';
	const TEMPLATE = 'cart';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		$fields = ['action|text'];
		self::sanitizeParametersShort($data, $fields);
		
		if ($data->action) {
			return self::process($data);
		}

		self::initPageHooks();
		self::appendJs($data);
		$items = Service::instance()->items();
		return self::display($data, $items);
	}

	public static function process(WireData $data) {
		$fields = ['action|text', 'itemID|string', 'qty|int'];
		self::sanitizeParametersShort($data, $fields);

		$CART = Service::instance();
		$CART->process($data);

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
		return self::pw('pages')->get('template=cart')->url;
	}

	public static function removeItemFromCartUrl($itemID) {
		return self::url() . '?' . http_build_query(['action' => 'remove-from-cart', 'itemID' => $itemID]);
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data, ObjectCollection $items) {
		return self::render($data, $items);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data, ObjectCollection $items) {
		return self::getTwig()->render('cart/page.twig', ['items' => $items]);
	}

/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	8. Supplemental
============================================================= */
	/**
	 * Return List of Script filepaths to be appended
	 * @param  WireData $data
	 * @return array
	 */
	protected static function getJsScriptPaths(WireData $data) {
		$jsPath = 'scripts/pages/cart/';
		$filenames = ['page.js'];
		$scripts = [];

		foreach ($filenames as $filename) {
			$scripts[] = $jsPath . $filename;
		}
		return $scripts;
	}

	protected static function appendJs(WireData $data, $scripts = []) {
		// self::appendJsJqueryValiudate();

		$scripts = self::getJsScriptPaths($data);
		parent::appendJs($data, $scripts);
	}

/* =============================================================
	9. Hooks / Object Decorating
============================================================= */
	/**
	 * Initialze Page Hooks
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPageHooks($tplname = '') {
		$selector = static::getPageHooksTemplateSelector($tplname);
		$m = self::pw('modules')->get('App');

		$m->addHook("$selector::removeItemFromCartUrl", function(HookEvent $event) {
			$event->return = self::removeItemFromCartUrl($event->arguments(0));
		});
	}

	/**
	 * Add Hooks to Pages
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPagesHooks() {
		$m = self::pw('modules')->get('App');

		// $m->addHook("Pages::logoutUrl", function($event) {
		// 	$event->return = self::logoutUrl($event->arguments(0));
		// });
	}
}