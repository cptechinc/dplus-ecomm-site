<?php namespace App\Ecomm\Services\ArInvoice\Data;
// Propel ORM Library
use Propel\Runtime\Collection\ObjectCollection;
// Dplus Models
use ArInvoice;
use Document;
// Pauldro ProcessWire
use Pauldro\ProcessWire\WireData;

/**
 * Invoice
 * Container for AR Invoice data
 * 
 * @property string    $invnbr
 * @property int       $ordn
 * @property ArInvoice $invoice
 * @property float     $paymentsTotal
 * @property float     $currentBalance
 * @property array     $periods         Pariods Data
 * @property ObjectCollection $payments
 * @property Document         $document
 */
class Invoice extends WireData {
	public function __construct() {
		$this->invnbr  = '';
		$this->ordn = 0;
		$this->invoice = false;
		$this->paymentsTotal = 0;
		$this->currentBalance = 0;
		$this->periods = [];
		$this->payments = new ObjectCollection();
		$this->document = new Document();
	}

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set Invoice
	 * @param  ArInvoice $r
	 * @return self
	 */
	public function setInvoice(ArInvoice $r) {
		$this->invnbr = $r->invoicenumber;
		return $this->set('invoice', $r);
	}

	/**
	 * Set Payments Total
	 * @param  float $total
	 * @return self
	 */
	public function setPaymentsTotal(float $total) {
		$this->set('paymentsTotal', $total);
		$this->currentBalance = $this->invoice->total - $this->paymentsTotal;
		return $this;
	}

	/**
	 * Set Periods Data
	 * @param  array $periods
	 * @return self
	 */
	public function setPeriods($periods = []) {
		return $this->set('periods', $periods);
	}
}