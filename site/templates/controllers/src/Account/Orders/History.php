<?php namespace Controllers\Account\Orders;
// Propel ORM Library
use Propel\Runtime\Util\PropelModelPager;
// Dplus Models
use SalesHistory as ShRecord;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInput;
// Dplus
use Dplus\Database\Tables\SalesOrder as SoTable;
use Dplus\Database\Tables\SalesHistory as ShTable;
// Controllers
// use Controllers\Abstracts\AbstractController;
// use Controllers\Account as AccountController;

/**
 * History
 * Handles the Sales History List page
 */
class History extends AbstractListController {
	const SESSION_NS    = 'sales-history';
	const TITLE         = 'Your Sales History';
	const PAGE_NAME     = 'history';

/* =============================================================
	1. Indexes
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return Filter Data
	 * @param  WireData $data
	 * @return SoTable\FilterData
	 */
	protected static function createFilterData(WireData $data) {
		/** @var WireInput */
		$input = self::pw('input');
		$filter = SoTable\FilterData::fromWireInputData($input->get);
		$filter->pagenbr = $input->pageNum();
		$filter->limit   = static::RESULTS_PERPAGE;
		return $filter;
	}
	
	/**
	 * Return List of Sales Orders
	 * @param WireData $data
	 * @return PropelModelPager[ShRecord]
	 */
	protected static function fetchListPaged(WireData $data) {
		/** @var WireInput */
		$input = self::pw('input');
		$filter = SoTable\FilterData::fromWireInputData($input->get);
		$filter->pagenbr = $input->pageNum();
		$filter->limit   = static::RESULTS_PERPAGE;
		$table = self::getOrdersTable();
		return $table->findPaginatedByFilterData($filter);
	}

/* =============================================================
	4. URLs
============================================================= */

/* =============================================================
	5. Displays
============================================================= */

/* =============================================================
	6. HTML Rendering
============================================================= */

/* =============================================================
	7. Class / Module Getters
============================================================= */
	/**
	 * Return Orders Table
	 * @return ShTable
	 */
	protected static function getOrdersTable() {
		return ShTable::instance();
	}
}