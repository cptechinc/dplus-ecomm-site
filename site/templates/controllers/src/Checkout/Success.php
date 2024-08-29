<?php namespace Controllers\Checkout;
// ProcessWire
use ProcessWire\WireArray;
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Checkout as Service;
use App\Ecomm\Services\Checkout\Data;
// Controllers
use Controllers\Abstracts\AbstractOrderingController;
use Controllers\Checkout;
use Controllers\Account\Orders\Order as OrderController;

/**
 * Checkout
 * Handles Checkout Page
 */
class Success extends AbstractOrderingController {
/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		if (self::init() === false) {
			return false;
		}
		self::getPwPage()->title = 'Thank You for your Order!';
		$form = Service::instance()->form();
		self::initPageHooks();
		return self::display($data, $form);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */
	protected static function init(WireData $data = null) {		
		if (Service::instance()->hasCheckoutOrdn() === false) {
			self::getPwSession()->redirect(Checkout::url());
			return false;
		}
		return true;
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data, Data\Form $form) {
		return self::render($data, $form);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data, Data\Form $form) {
		return self::getTwig()->render('checkout/success/page.twig', ['form' => $form]);
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
		$selector = static::getPageHooksTemplateSelector();
		$m = self::pw('modules')->get('App');

		$m->addHook("$selector::orderUrl", function($event) {
			$event->return = OrderController::urlOrder($event->arguments(0));
		});
	}
}