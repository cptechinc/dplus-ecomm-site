<?php namespace App\Ecomm\Services\Cart\Data;
// Propel ORM Library
use Propel\Runtime\Collection\ObjectCollection;
// Dpluso Model
use Cart as CartItemRecord;
// ProcessWire
use ProcessWire\WireData;

/**
 * Cart
 * Wrapper for Cart Data
 * 
 * @property ObjectCollection[CartItemRecord] $items
 * @property WireData                         $totals
 */
class Cart extends WireData {
	public function __construct() {
		$this->items = [];
		$this->totals = new Totals();
	}
}