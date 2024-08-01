<?php namespace Controllers;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Cart;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * Product
 * Handles Product Pages
 */
class Product extends AbstractController {
	const SESSION_NS = 'product';
	const TEMPLATE = 'product';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		self::initPageHooks();
		return self::display($data);
	}

	public static function process(WireData $data) {
		$fields = ['action|text', 'itemID|string', 'qty|int'];
		self::sanitizeParametersShort($data, $fields);

		switch ($data->action) {
			case 'add-to-cart':
				$SERVICE = Cart::instance();
				$SERVICE->addToCart($data->itemID, $data->qty);
				self::getPwSession()->redirect(self::getPwPage()->url, $http301=false);
				break;
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
		return self::getTwig()->render('products/product/page.twig');
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
		$selector = static::getPageHooksTemplateSelector();
		$m = self::pw('modules')->get('App');

		// $m->addHook("$selector::forgotPasswordUrl", function($event) {
		// 	$event->return = AccountController\ForgotPassword::url();
		// });

		// $m->addHook("$selector::registerUrl", function($event) {
		// 	$event->return = AccountController\Register::url();
		// });
	}

	/**
	 * Add Hooks to Pages
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPagesHooks() {
		$m = self::pw('modules')->get('App');

		$m->addHook("Pages::logoutUrl", function($event) {
			$event->return = self::logoutUrl($event->arguments(0));
		});
	}
}