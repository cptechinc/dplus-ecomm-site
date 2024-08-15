<?php namespace App\Ecomm\Services\Cart\Data;
// ProcessWire
use ProcessWire\WireData;

/**
 * Cart
 * Wrapper for Cart Totals Data
 * 
 * @property float  $shipping
 * @property float  $subtotal
 * @property float  $tax
 */
class Totals extends WireData {

	public function __construct() {
		$this->shipping = 0.00;
		$this->subtotal = 0.00;
		$this->tax      = 0.00;
	}

	/**
	 * Return the Sum of all costs
	 * @return float
	 */
	public function total() {
		return $this->shipping + $this->subtotal + $this->tax;
	}
}