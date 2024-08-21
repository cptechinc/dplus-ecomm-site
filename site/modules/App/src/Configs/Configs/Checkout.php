<?php namespace App\Configs\Configs;
// ProcessWire
use ProcessWire\WireArray;

/**
 * Checkout
 * Container for Checkout Config
 * 
 * @property WireArray $allowedPaymentMethods
 * @property WireArray $allowedCreditCards
 */
class Checkout extends AbstractConfig {
	const ALLOWED_PAYMENTMETHODS = [
		'bill' => 'Bill to Account',
		'cc'   => 'Credit Card',
		'cod'  =>'Collect on Delivery'
	];
	const ALLOWED_CREDITCARDS = [
		'amex'       => 'American Express',
		'discover'   => 'Discover',
		'visa'       => 'Visa',
		'mastercard' => 'MasterCard',
	];
	public function __construct() {
		$this->allowedPaymentMethods = new WireArray();
		$this->allowedPaymentMethods->setArray(self::ALLOWED_PAYMENTMETHODS);

		$this->allowedCreditCards = new WireArray();
		$this->allowedCreditCards->setArray(self::ALLOWED_CREDITCARDS);
	}
}