<?php namespace Controllers\Account\Invoices;
// Propel ORM Library
use Propel\Runtime\Util\PropelModelPager;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\WireData;
use ProcessWire\Wire404Exception;
// Dpay
use Dpay\Db\Tables\PaymentLinks;
// Dplus
use Dplus\Database\Tables\ArInvoice as InvoicesTable;


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
		// $m = self::pw('modules')->get('App');

		// $m->addHook("$selector::listUrl", function($event) {
		// 	$event->return = static::listUrl();
		// });

		// $m->addHook("$selector::documentUrl", function(HookEvent $event) {
		// 	$event->return = static::urlDocumentDownload($event->arguments(0), $event->arguments(1), $event->arguments(2));
		// });

		// $m->addHook("$selector::countOrderDocuments", function(HookEvent $event) {
		// 	$event->return = DOCM::count($event->arguments(0));
		// });

		// $m->addHook("$selector::findOrderDocuments", function(HookEvent $event) {
		// 	$event->return = DOCM::find($event->arguments(0));
		// });

		// $m->addHook("$selector::hasPaymentLink", function(HookEvent $event) {
		// 	$event->return = PaymentLinks::instance()->existsByOrdn($event->arguments(0));
		// });

		// $m->addHook("$selector::fetchPaymentLink", function(HookEvent $event) {
		// 	$event->return = PaymentLinks::instance()->recordByOrdn($event->arguments(0));
		// });
	}
}