<?php namespace Controllers\Account\Orders;
// Dplus Models
use SalesOrder as SoRecord;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\SalesOrder as SoTable;

/**
 * Order
 * Handles the Sales Order Page
 * 
 * @static SoRecord fetchOrder(WireData $data)
 */
class Order extends AbstractOrderController {
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