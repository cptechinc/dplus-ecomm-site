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

/**
 * Orders
 * Handles the Orders List page
 */
class Orders extends AbstractListController {
	const SESSION_NS = 'sales-orders';
	const TITLE         = 'Your Open Orders';
	const PAGE_NAME     = 'orders';

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
	 * @return PropelModelPager[SoRecord]
	 */
	protected static function fetchListPaged(WireData $data) {
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
	 * @return SoTable
	 */
	protected static function getOrdersTable() {
		return SoTable::instance();
	}
}