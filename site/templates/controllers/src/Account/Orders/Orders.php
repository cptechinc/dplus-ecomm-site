<?php namespace Controllers\Account\Orders;
// Propel ORM Library
use Propel\Runtime\Util\PropelModelPager;
// Dplus Models
use SalesOrder as SoRecord;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInput;
// Dplus
use Dplus\Database\Tables\SalesOrder as SoTable;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Account as AccountController;

/**
 * Orders
 * Handles the Orders, List and Page
 */
class Orders extends AbstractController {
	const SESSION_NS = 'sales-orders';
	const REQUIRE_LOGIN = true;
	const TEMPLATE      = 'account';
	const TITLE         = 'Your Open Orders';
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
		return static::list($data);
	}

	/**
	 * Handle Display of List Page
	 * @param  WireData $data
	 * @return bool
	 */
	private static function list(WireData $data) {
		$results = static::fetchListPaged($data);
		return self::displayList($data, $results);
	}

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return List of Sales Orders
	 * @param WireData $data
	 * @return PropelModelPager[SoRecord]
	 */
	private static function fetchListPaged(WireData $data) {
		/** @var WireInput */
		$input = self::pw('input');
		$filter = SoTable\FilterData::fromWireInputData($input->get);
		$filter->pagenbr = $input->pageNum();
		$filter->limit   = static::RESULTS_PERPAGE;
		$table = SoTable::instance();
		return $table->findPaginatedByFilterData($filter);
	}

/* =============================================================
	4. URLs
============================================================= */
	public static function url() {
		return AccountController::url() . 'orders/';
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
}