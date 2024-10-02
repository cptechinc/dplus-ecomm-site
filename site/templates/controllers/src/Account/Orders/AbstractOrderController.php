<?php namespace Controllers\Account\Orders;
// Propel ORM Library
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as AbstractOrder;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\AbstractOrderTable;
use Dplus\Docm\Finders\SalesOrder as DOCM;
// App
use App\Ecomm\Services\Dpay\PaymentLinks;
// Controllers
use Controllers\Abstracts\AbstractController;


/**
 * AbstractOrderController
 * Template for handling the Order page
 */
abstract class AbstractOrderController extends AbstractController {
	const SESSION_NS = 'sales-order';
	const REQUIRE_LOGIN = true;
	const TEMPLATE      = 'account';
	const TITLE         = 'Sales Order';
	const PAGE_NAME     = 'order';

/* =============================================================
	1. Indexes
============================================================= */
	/**
	 * Process HTTP GET Request
	 * @param  WireData $data
	 * @return string|bool
	 */
	public static function index(WireData $data) {
		self::sanitizeParametersShort($data, ['ordn|int', 'action|text']);
		if (static::init($data) === false) {
			return false;
		}
		if ($data->action) {
			return static::process($data);
		}
		self::pw('page')->title = "Order #$data->ordn";
		static::initHooks();
		return static::order($data);
	}

	protected static function process(WireData $data) {
		self::sanitizeParametersShort($data, ['ordn|int', 'action|text']);
		self::pw('session')->redirect(self::urlOrder($data->ordn), $http301=false);
	}

	/**
	 * Handle Display of Order Page
	 * @param  WireData $data
	 * @return bool
	 */
	protected static function order(WireData $data) {
		$order = static::fetchOrder($data);
		return static::displayOrder($data, $order);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */
	/**
	 * Init
	 * @return bool
	 */
	protected static function init(WireData $data = null) {
		if (static::initLogin($data) === false) {
			return false;
		}
		// Validate Order Exists / and user can access order via their custid
		if (static::getOrdersTable()->isForCustid($data->ordn, self::getEcUser()->custid) === false) {
			self::pw('session')->redirect(static::listUrl(), $http301=false);
			return false;
		}
		self::pw('input')->get->ordn = $data->ordn;
		self::pw('page')->ordn       = $data->ordn;
		return true;
	}

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return Order
	 * @param  WireData $data
	 * @return AbstractOrder
	 */
	protected static function fetchOrder(WireData $data) {
		$TABLE  = static::getOrdersTable();
		return $TABLE->order($data->ordn);
	}

/* =============================================================
	4. URLs
============================================================= */
	/**
	 * Return URL to List Page
	 * @return string
	 */
	protected static function listUrl() {
		return Orders::url();
	}

	/**
	 * Return Base URL
	 * @return string
	 */
	public static function url() {
		return static::listUrl();
	}

	/**
	 * Return URL to Order Page
	 * @param  string $ordn
	 * @return string
	 */
	public static function urlOrder($ordn) {
		return static::url() . "$ordn/";
	}

	/**
	 * Return URL to Order Page
	 * @param  string $ordn
	 * @return string
	 */
	public static function urlDocuments($ordn) {
		return static::urlOrder($ordn) . "documents/";
	}

	/**
	 * Return URL to download Order Document
	 * @param  string $ordn
	 * @param  string $folder
	 * @param  string $filename
	 * @return string
	 */
	public static function urlDocumentDownload($ordn, $folder, $filename) {
		return OrderDocuments::urlDownload($ordn, $folder, $filename);
	}

	/**
	 * Return URL to create payment link for a single order
	 * @param  int $ordn
	 * @return string
	 */
	public static function urlCreatePaymentLink($ordn) {
		return static::urlOrder($ordn) . '?' . http_build_query(['action' => 'create-paymentlink-single-order', 'ordn' => $ordn]);
	}
	
/* =============================================================
	5. Displays
============================================================= */
	protected static function displayOrder(WireData $data, AbstractOrder $order) {
		return static::renderOrder($data, $order);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	protected static function renderOrder(WireData $data, AbstractOrder $order) {
		return self::getTwig()->render('account/orders/order/' . static::PAGE_NAME . '/page.twig', ['order' => $order]);
	}

/* =============================================================
	7. Class / Module Getters
============================================================= */
	/**
	 * Return Orders Table
	 * @return AbstractOrderTable
	 */
	abstract protected static function getOrdersTable();

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

		$m->addHook("$selector::listUrl", function($event) {
			$event->return = static::listUrl();
		});

		$m->addHook("$selector::documentUrl", function(HookEvent $event) {
			$event->return = static::urlDocumentDownload($event->arguments(0), $event->arguments(1), $event->arguments(2));
		});

		$m->addHook("$selector::countOrderDocuments", function(HookEvent $event) {
			$event->return = DOCM::count($event->arguments(0));
		});

		$m->addHook("$selector::findOrderDocuments", function(HookEvent $event) {
			$event->return = DOCM::find($event->arguments(0));
		});

		$m->addHook("$selector::hasPaymentLink", function(HookEvent $event) {
			$event->return = PaymentLinks::instance()->existsByOrdn($event->arguments(0));
		});

		$m->addHook("$selector::fetchPaymentLink", function(HookEvent $event) {
			$event->return = PaymentLinks::instance()->paymentLinkByOrdn($event->arguments(0));
		});

		$m->addHook("$selector::createOrderPaymentLink", function(HookEvent $event) {
			$event->return = static::urlCreatePaymentLink($event->arguments(0));
		});
	}
}