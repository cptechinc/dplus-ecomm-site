<?php namespace Controllers\Account\Orders;
// Propel ORM Library
use Propel\Runtime\Util\PropelModelPager;
// Dplus Models
use SalesOrder as SoRecord;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\SalesOrder as SoTable;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Account as AccountController;


class Orders extends AbstractController {
	const SESSION_NS = 'sales-orders';
	const REQUIRE_LOGIN = true;
	const TEMPLATE      = 'account';
	const TITLE         = 'Your Open Orders';
	const PAGE_NAME     = 'orders';

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
		// static::appendJs($data);
		return static::list($data);
	}

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
		$filter = SoTable\FilterData::fromWireInputData(self::pw('input')->get);
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