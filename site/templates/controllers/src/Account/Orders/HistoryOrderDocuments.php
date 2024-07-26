<?php namespace Controllers\Account\Orders;
// Dplus Models
	// use SalesHistory as ShRecord;
// ProcessWire
	// use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\SalesHistory as ShTable;

/**
 * HistoryOrderDocuments
 * Handles the Sales History Documents Order Page
 */
class HistoryOrderDocuments extends AbstractOrderDocumentsController {
	const SESSION_NS = 'sales-order';
	const TITLE         = 'Sales Order';
	const PAGE_NAME     = 'history';

/* =============================================================
	4. URLs
============================================================= */
	/**
	 * Return URL to List Page
	 * @return string
	 */
	protected static function listUrl() {
		return HistoryList::url();
	}
	
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