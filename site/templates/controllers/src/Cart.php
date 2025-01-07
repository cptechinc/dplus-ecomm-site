<?php namespace Controllers;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Cart as Service;
use App\Ecomm\Services\Cart\Data;
// Controllers
use Controllers\Abstracts\AbstractOrderingController;
use ProcessWire\HookEvent;

/**
 * Cart
 * Handles Cart Page
 */
class Cart extends AbstractOrderingController {
	const SESSION_NS = 'cart';
	const TEMPLATE   = 'cart';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		if (self::init() === false) {
			return false;
		}
		$fields = ['action|text'];
		self::sanitizeParametersShort($data, $fields);
		
		if ($data->action) {
			return self::process($data);
		}

		self::initPageHooks();
		self::appendJs($data);
		$cart = Service::instance()->cart();
		return self::display($data, $cart);
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
		$filenames = ['classes/Requests.js', 'form-add-to-cart/classes/Form.js', 'form-add-to-cart/form.js', 'page.js'];
		$scripts = [];

		foreach ($filenames as $filename) {
			$scripts[] = $jsPath . $filename;
		}
		return $scripts;
	}

	protected static function appendJs(WireData $data, $scripts = []) {
		self::appendJsJqueryValiudate();

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

		$m->addHook("$selector::checkoutUrl", function(HookEvent $event) {
			$event->return = Checkout::url();
		});

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