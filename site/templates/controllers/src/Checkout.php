<?php namespace Controllers;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Cart as CartService;
use App\Ecomm\Services\Cart\Data;
// Controllers
use Controllers\Abstracts\AbstractOrderingController;
use ProcessWire\HookEvent;

/**
 * Checkout
 * Handles Checkout Page
 */
class Checkout extends AbstractOrderingController {
	const SESSION_NS = 'cart';
	const TEMPLATE   = 'cart';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		if (self::init() === false) {
			echo 'sdfs';
			return false;
		}
		$fields = ['action|text'];
		self::sanitizeParametersShort($data, $fields);
		
		if ($data->action) {
			return self::process($data);
		}

		return 'fun';

		self::initPageHooks();
		self::appendJs($data);
		// $cart = Service::instance()->cart();
		// return self::display($data, $cart);
	}

	public static function process(WireData $data) {
		if (self::init() === false) {
			return false;
		}
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
	protected static function init(WireData $data = null) {
		if (parent::init($data) === false) {
			return false;
		}
		return self::initCart($data);
	}

	/**
	 * Check if ordering is allowed, if so redirect to home page
	 * @return bool
	 */
	protected static function initCart(WireData $data = null) {
		if (CartService::instance()->countItems() < 1) {
			self::getPwSession()->redirect(Cart::url(), $http301=false);
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
	public static function url() {
		return self::pw('pages')->get('template=cart')->url;
	}

	public static function removeItemFromCartUrl($itemID) {
		return self::url() . '?' . http_build_query(['action' => 'remove-from-cart', 'itemID' => $itemID]);
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data, Data\Cart $cart) {
		return self::render($data, $cart);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data, Data\Cart $cart) {
		return self::getTwig()->render('cart/page.twig', ['cart' => $cart]);
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