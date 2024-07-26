<?php namespace Controllers\Account\Orders;
// Dplus Models
use SalesHistory as ShRecord;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\SalesHistory as ShTable;

/**
 * HistoryOrder
 * Handles the Sales History Order Page
 * 
 * @static ShRecord fetchOrder(WireData $data)
 */
class HistoryOrder extends AbstractOrderController {
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

	/**
	 * Return URL to download Order Document
	 * @param  string $ordn
	 * @param  string $folder
	 * @param  string $filename
	 * @return string
	 */
	public static function urlDocumentDownload($ordn, $folder, $filename) {
		return HistoryOrderDocuments::urlDownload($ordn, $folder, $filename);
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