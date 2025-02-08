<?php namespace Controllers\Account\Invoices;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\PaginatedArray;
use ProcessWire\WireData;
use ProcessWire\Wire404Exception;
// Dpay
use Dpay\Db\Tables\PaymentLinks\FilterData;
// Dplus
use Dplus\Database\Tables\SalesHistory as OrderHistoryTable;
// App
use App\Ecomm\Services\Dpay\PaymentLinks as Service;
// Controllers
use Controllers\Account\Orders\HistoryOrder;

/**
 * PaymentLinks
 * Template for handling the PaymentLinks pages
 */
class PaymentLinks extends AbstractController {
	const SESSION_NS = 'payment-links';
	const REQUIRE_LOGIN = true;
	const TEMPLATE      = 'account';
	const TITLE         = 'Payment Links';
	const RESULTS_PERPAGE = 25;

/* =============================================================
	1. Indexes
============================================================= */
	/**
	 * Process HTTP GET Request
	 * @param  WireData $data
	 * @return string|bool
	 */
	public static function index(WireData $data) {
		if (static::init($data) === false) {
			return false;
		}
		self::sanitizeParametersShort($data, ['id|text']);
		self::pw('page')->title = self::TITLE;
		static::initHooks();
		return static::list($data);
	}

	/**
	 * Handle Display of Order Page
	 * @param  WireData $data
	 * @return string
	 */
	protected static function list(WireData $data) {
		$list = self::fetchList($data);
		return self::displayList($data, $list);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */
	/**
	 * Init
	 * @return bool
	 */
	protected static function init(WireData $data = null) {
		if (parent::init($data) === false) {
			return false;
		}
		return self::initPaymentLinks($data);
	}

	/**
	 * Initialize Payment Links
	 * @throws Wire404Exception
	 * @param  WireData|null $data
	 * @return bool
	 */
	private static function initPaymentLinks(WireData $data = null) {
		$config = self::getPwConfig();
		if ($config->app->useDpay) {
			return true;
		}
		throw new Wire404Exception();
		return false;
	}

	/**
	 * Return if Sales Order # exists
	 * @param  int $ordn
	 * @return bool
	 */
	private static function orderExists($ordn) {
		return OrderHistoryTable::instance()->exists($ordn);
	}

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return Filter Data
	 * @param  WireData $data
	 * @return FilterData
	 */
	protected static function createFilterData(WireData $data) {
		/** @var WireInput */
		$input = self::pw('input');
		$filter = FilterData::fromWireInputData($input->get);
		$filter->pagenbr = $input->pageNum();
		$filter->limit   = static::RESULTS_PERPAGE;
		return $filter;
	}
	
	/**
	 * Return List of Payment Links
	 * @param  WireData $data
	 * @return PaginatedArray
	 */
	private static function fetchList(WireData $data) {
		$filter = static::createFilterData($data);
		$TABLE  = Service::instance();

		$list   = new PaginatedArray();
		$list->setLimit($filter->limit);
		$list->setStart($filter->offset());
		$list->setTotal($TABLE->count($filter));
		$list->setArray($TABLE->find($filter));
		return $list;
	}

/* =============================================================
	4. URLs
============================================================= */
	/**
	 * Return Base URL
	 * @return string
	 */
	public static function url() {
		return parent::url() . 'payment-links/';
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function displayList(WireData $data, PaginatedArray $list) {
		return self::renderList($data, $list);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function renderList(WireData $data, PaginatedArray $list) {
		return self::getTwig()->render('account/invoices/payment-links/page.twig', ['results' => $list]);
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

		$m->addHook("$selector::orderExists", function(HookEvent $event) {
			$event->return = static::orderExists(intval($event->arguments(0)));
		});

		$m->addHook("$selector::orderUrl", function(HookEvent $event) {
			$event->return = HistoryOrder::urlOrder($event->arguments(0));
		});
	}
}