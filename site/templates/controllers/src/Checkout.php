<?php namespace Controllers;
// ProcessWire
use ProcessWire\WireArray;
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Cart as CartService;
use App\Ecomm\Services\Checkout as Service;
use App\Ecomm\Services\Checkout\Data;
// Controllers
use Controllers\Abstracts\AbstractOrderingController;
use ProcessWire\HookEvent;

/**
 * Checkout
 * Handles Checkout Page
 */
class Checkout extends AbstractOrderingController {
	const SESSION_NS = 'checkout';
	const TEMPLATE   = 'checkout';
	const STEPS = [
		'address'  => ['title' => 'Address', 'icon' => 'map-location'],
		'shipping' => ['title' => 'Shipping Method', 'icon' => 'shipping'],
		'payment'  => ['title' => 'Payment', 'icon' => 'payment'],
		'review'   => ['title' => 'Order Review', 'icon' => 'show']
	];

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
		return self::checkout($data);
	}

	public static function process(WireData $data) {
		if (self::init() === false) {
			return false;
		}
		$fields = ['action|text'];
		self::sanitizeParametersShort($data, $fields);

		$SERVICE = Service::instance();
		$data->success = $SERVICE->process($data);
		self::updateCompletedStepsAfterProcessing($data);
		$url = self::url();

		switch($data->action) {
			case 'submit-order':
				if ($data->success) {
					$url = self::url() . 'confirmation/';
				}
				break;
		}
		self::getPwSession()->redirect($url, $http301=false);
		return true;
	}

	private static function checkout(WireData $data) {
		self::initCompletedSteps();
		$form = self::fetchFormData($data);
	
		self::initPageHooks();
		self::appendJs($data);
		self::addVarsToJsVars($data);
		return self::display($data, $form);
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

	/**
	 * Initialize Completed Steps DAta
	 * @return bool
	 */
	public static function initCompletedSteps() {
		if (empty(self::getSessionVar('steps')) === false) {
			return true;
		}
		$steps = new WireData();
		$steps->address  = false;
		$steps->shipping = false;
		$steps->payment  = false;
		$steps->index    = 0;
		self::setSessionVar('steps', $steps->data);
		return true;
	}

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return Checkout Form Data
	 * @param  WireData $data
	 * @return Service\Data\Form
	 */
	private static function fetchFormData(WireData $data) {
		$SERVICE = Service::instance();

		if ($SERVICE->hasCheckout() === false) {
			$SERVICE->initCheckout();
		}
		return $SERVICE->form();
	}

	/**
	 * Return List of Checkout Steps
	 * @param  WireData $data
	 * @return WireArray
	 */
	private static function fetchCheckoutSteps(WireData $data = null) {
		$steps = new WireArray();

		foreach (self::STEPS as $key => $step) {
			$stepData = new WireData();
			$stepData->setArray($step);
			$stepData->name = $key;
			$stepData->id   = $key;
			$steps->set($key, $stepData);
		}
		return $steps;
	}

	/**
	 * Return Completed Steps from Session
	 * @return WireData
	 */
	public static function fetchCompletedSteps() {
		$steps = new WireData();
		$steps->address  = false;
		$steps->shipping = false;
		$steps->payment  = false;
		$steps->index    = 0;
		$data = self::getSessionVar('steps');

		if (empty($data)) {
			return $steps;
		}
		$steps->setArray($data);
		return $steps;
	}

/* =============================================================
	4. URLs
============================================================= */
	public static function url() {
		return self::pw('pages')->get('template=checkout')->url;
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data, Data\Form $form) {
		return self::render($data, $form, CartService::instance()->cart());
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data, Data\Form $form, CartService\Data\Cart $cart) {
		return self::getTwig()->render('checkout/page.twig', ['form' => $form, 'cart' => $cart]);
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
		$jsPath = 'scripts/pages/checkout/';
		$filenames = [
			'classes/Requests.js', 
			'address/classes/Form.js', 'address/form.js',
			'payment/classes/Form.js', 'payment/form.js',
			'shipping/form.js'
		];
		$scripts = [];

		foreach ($filenames as $filename) {
			$scripts[] = $jsPath . $filename;
		}
		return $scripts;
	}

	protected static function appendJs(WireData $data, $scripts = []) {
		self::appendJsJqueryValiudate();
		self::appendJsJqueryPayment();

		$scripts = self::getJsScriptPaths($data);
		parent::appendJs($data, $scripts);
	}

	/**
	 * Update Completed Steps after Processing
	 * @param  WireData $data
	 * @return bool
	 */
	public static function updateCompletedStepsAfterProcessing(WireData $data) {
		if (empty($data->success)) {
			return false;
		}

		$steps = self::fetchCheckoutSteps();
		$completed = self::fetchCompletedSteps();
		$stepsKeys = $steps->getKeys();
		
		switch($data->action) {
			case 'update-address':
				$completed->address = true;
				$completed->index = array_search('address', $stepsKeys) + 1;
				self::setSessionVar('steps', $completed->data);
				return true;
				break;
			case 'update-shipping':
				$completed->shipping = true;
				$completed->index = array_search('shipping', $stepsKeys) + 1;
				self::setSessionVar('steps', $completed->data);
				return true;
				break;
			case 'update-payment':
				$completed->payment = true;
				$completed->index = array_search('payment', $stepsKeys) + 1;
				self::setSessionVar('steps', $completed->data);
				return true;
				break;
		}
	}

	/**
	 * Append jQuery Payment scripts
	 * @return bool
	 */
	public static function appendJsJqueryPayment() {
		$fh     = self::getFileHasher();
		$config = self::getPwConfig();

		$config->scripts->append($fh->getHashUrl('vendor/jquery.payment/jquery.payment.js'));
		return true;
	}

	/**
	 * Add Variables to JS Vars Array
	 * @param  WireData $data
	 * @return void
	 */
	protected static function addVarsToJsVars(WireData $data) {
		$jsVars = self::pw('config')->js('vars');
		$jsVars['CHECKOUT'] = static::checkoutJsVarsArray($data);
		self::pw('config')->js('vars', $jsVars);
	}

	/**
	 * Return Array for JS vars for CodeTable
	 * @param  WireData $data
	 * @return array
	 */
	protected static function checkoutJsVarsArray(WireData $data) {
		$config = self::getPwConfig();
		
		return [
			'config' => [
				'allowedcardtypes' => $config->checkout->allowedCreditCards->getArray(),
				'fields' => [
					// 'binid' => ['blankid' => $SOP::BLANK_BIN_ALIAS]
				]
			]
		];
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

		$m->addHook("$selector::checkoutSteps", function(HookEvent $event) {
			$event->return = self::fetchCheckoutSteps();
		});
	}
}