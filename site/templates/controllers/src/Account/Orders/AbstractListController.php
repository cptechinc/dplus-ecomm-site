<?php namespace Controllers\Account\Orders;
// Propel ORM Library
use Propel\Runtime\Util\PropelModelPager;
// Dplus Models
use SalesOrder as SoRecord;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\WireData;
// Dplus
use Dplus\Abstracts\AbstractFilterData;
use Dplus\Database\Tables\AbstractOrderTable;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Account as AccountController;

/**
 * AbstractListController
 * Template for handling the Order Lists pages
 */
abstract class AbstractListController extends AbstractController {
	const SESSION_NS = 'sales-orders';
	const REQUIRE_LOGIN = true;
	const TEMPLATE      = 'account';
	const TITLE         = 'Your Orders';
	const PAGE_NAME     = 'orders';
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
		if (static::init() === false) {
			return false;
		}
		self::pw('page')->title = static::TITLE;
		static::initPageHooks();
		return static::list($data);
	}

	/**
	 * Handle Display of List Page
	 * @param  WireData $data
	 * @return bool
	 */
	protected static function list(WireData $data) {
		$results = static::fetchListPaged($data);
		return static::displayList($data, $results);
	}

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return Filter Data
	 * @param  WireData $data
	 * @return AbstractFilterData
	 */
	abstract protected static function createFilterData(WireData $data);

	/**
	 * Return List of Sales Orders
	 * @param WireData $data
	 * @return PropelModelPager[SoRecord]
	 */
	protected static function fetchListPaged(WireData $data) {
		$filter = static::createFilterData($data);
		$TABLE  = static::getOrdersTable();
		return $TABLE->findPaginatedByFilterData($filter);
	}

/* =============================================================
	4. URLs
============================================================= */
	public static function url() {
		return AccountController::url() . static::PAGE_NAME . '/';
	}

	public static function orderUrl($ordn) {
		return Order::urlOrder($ordn);
	}
	
/* =============================================================
	5. Displays
============================================================= */
	protected static function displayList(WireData $data, PropelModelPager $results) {
		return static::renderList($data, $results);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	protected static function renderList(WireData $data, PropelModelPager $results) {
		return self::getTwig()->render('account/orders/' . static::PAGE_NAME . '/list/page.twig', ['results' => $results]);
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

		$m->addHook("$selector::orderUrl", function(HookEvent $event) {
			$event->return = static::orderUrl($event->arguments(0));
		});
	}
}