<?php namespace Controllers\Account\Orders;
// Dplus Models
use SalesOrder as SoRecord;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\SalesOrder as SoTable;

/**
 * OrderDocuments
 * Handles the Sales Order Documents Page
 * 
 * @static SoRecord fetchOrder(WireData $data)
 */
class OrderDocuments extends AbstractOrderDocumentsController {
	const SESSION_NS = 'sales-order';
	const TITLE      = 'Sales Order';
	const PAGE_NAME  = 'order';
	
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