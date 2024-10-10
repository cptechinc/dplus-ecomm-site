<?php namespace Controllers\Account\Invoices;
// Propel ORM Library
use Propel\Runtime\Util\PropelModelPager;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\WireData;
use ProcessWire\Wire404Exception;
// Dplus
use Dplus\Database\Tables\ArInvoice as InvoicesTable;
use Dplus\Database\Tables\SalesHistory as OrderHistoryTable;
// App
use App\Ecomm\Services\Dpay\PaymentLinks;
// Controllers
use Controllers\Account\Orders\HistoryOrder;

/**
 * OpenInvoices
 * Template for handling the Order page
 */
class OpenInvoices extends AbstractController {
	const SESSION_NS = 'open-invoices';
	const REQUIRE_LOGIN = true;
	const TEMPLATE      = 'account';
	const TITLE         = 'Open Invoices';
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
		self::sanitizeParametersShort($data, ['ordn|int']);
		self::pw('page')->title = self::TITLE;
		static::initHooks();
		self::appendJs($data);
		return static::list($data);
	}

	/**
	 * Handle Display of Order Page
	 * @param  WireData $data
	 * @return bool
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
		return self::initOpenInvoices($data);
	}

	private static function initOpenInvoices(WireData $data = null) {
		$config = self::getPwConfig();
		if ($config->app->showOpenInvoices) {
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
	 * @return InvoicesTable\FilterData
	 */
	protected static function createFilterData(WireData $data) {
		/** @var WireInput */
		$input = self::pw('input');
		$filter = InvoicesTable\FilterData::fromWireInputData($input->get);
		$filter->pagenbr = $input->pageNum();
		$filter->limit   = static::RESULTS_PERPAGE;
		return $filter;
	}
	
	/**
	 * Return List of AR Invoices
	 * @param  WireData $data
	 * @return PropelModelPager
	 */
	private static function fetchList(WireData $data) {
		$filter = static::createFilterData($data);
		$TABLE  = InvoicesTable::instance();
		return $TABLE->findPaginatedByFilterData($filter);
	}

/* =============================================================
	4. URLs
============================================================= */
	/**
	 * Return Base URL
	 * @return string
	 */
	public static function url() {
		return parent::url() . 'open/';
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function displayList(WireData $data, PropelModelPager $list) {
		return self::renderList($data,$list);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function renderList(WireData $data, PropelModelPager $list) {
		return self::getTwig()->render('account/invoices/open/page.twig', ['results' => $list]);
	}

/* =============================================================
	7. Class / Module Getters
============================================================= */
	/**
	 * Return List of Script filepaths to be appended
	 * @param  WireData $data
	 * @return array
	 */
	protected static function getJsScriptPaths(WireData $data) {
		$jsPath = 'scripts/pages/' . self::getNamespaceClassNameAsPath();
		$filenames = [
			'create-multi-order-paymentlink/form.js',
			'create-multi-order-paymentlink/events.js'
		];
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

		$m->addHook("$selector::hasPaymentLink", function(HookEvent $event) {
			$event->return = PaymentLinks::instance()->existsByOrdn(intval($event->arguments(0)));
		});

		$m->addHook("$selector::fetchPaymentLink", function(HookEvent $event) {
			$event->return = PaymentLinks::instance()->paymentLinkByOrdn(intval($event->arguments(0)));
		});
	}
}