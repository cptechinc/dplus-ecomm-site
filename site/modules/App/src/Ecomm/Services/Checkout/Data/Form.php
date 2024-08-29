<?php namespace App\Ecomm\Services\Checkout\Data;
// Dpluso Models
use Billing;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;

/**
 * Form
 * Container for Checkout Form Data
 * 
 * @property bool   $error
 * @property string $errormsg
 * @property string $billtoname     Bill-To Name
 * @property string $billtocompany  Bill-To Company
 * @property string $billtoaddress1 Bill-To Address Line 1
 * @property string $billtoaddress2 Bill-To Address Line 2
 * @property string $billtocity     Bill-To Address City
 * @property string $billtostate    Bill-To Address State
 * @property string $billtozip      Bill-To Address Zip
 * // Ship-To
 * @property string $shiptoid       Ship-To ID
 * @property string $shiptoname     Ship-To Name
 * @property string $shiptocompany  Ship-To Company
 * @property string $shiptoaddress1 Ship-To Address Line 1
 * @property string $shiptoaddress2 Ship-To Address Line 2
 * @property string $shiptocity     Ship-To Address City
 * @property string $shiptostate    Ship-To Address State
 * @property string $shiptozip      Ship-To Address Zip
 * // Contact
 * @property string $phonenbr       Contact Phone Number
 * @property string $email          Contact E-mail
 * // Shipping
 * @property bool   $shipcomplete   Ship Order complete?
 * @property string $custpo         Customer Refereence / PO #
 * @property string $shipviacod e   Ship-Via
 * @property string $notes          Order Notes
 * // Payment
 * @property string $termscodetype  Terms Type
 * @property string $paymentmethod  Payment Method
 * @property string $cclast4        Last 4 Digits of Credit Card
 * @property string $cardnumber     Card Number *** only used from User Input ***
 * @property string $expiredate     Card Expiry Date *** only used from User Input ***
 * @property string $cvc            Card Validation / Security Code *** only used from User Input ***
 * @property string $cardtype       Card Type
 */
class Form extends WireData {
	const DEFAULT_TERMSCODETYPE = 'STD';
	const DEFAULT_PAYMENTMETHOD = 'cc';
	const FIELDS_STRING = [
		'billtoname', 'billtocompany',
		'billtoaddress1', 'billtoaddress2',
		'billtocity', 'billtostate', 'billtozip',
		'shiptoid', 'shiptoname', 'shiptocompany',
		'shiptoaddress1', 'shiptoaddress2',
		'shiptocity', 'shiptostate', 'shiptozip',
		'phonenbr', 'email',
		'custpo', 'shipviacode', 'notes',
		'termscodetype', 'paymentmethod',
		'cclast4', 'cardnumber', 'expiredate', 'cvc', 'cardtype'
	];
	const BILLING_KEYMAP = [
		'errormsg'       => 'ermes',
		'billtoname'     => 'billtoname',
		'billtocompany'  => 'billtocompany',
		'billtoaddress1' => 'billtoaddress1',
		'billtoaddress2' => 'billtoaddress2',
		'billtocity'     => 'billtocity',
		'billtostate'    => 'billtostate',
		'billtozip'      => 'billtozip',
		'shiptoid'       => 'shiptoid',
		'shiptoname'     => 'shiptoname',
		'shiptocompany'  => 'shiptocompany',
		'shiptoaddress1' => 'shiptoaddress1',
		'shiptoaddress2' => 'shiptoaddress2',
		'shiptocity'     => 'shiptocity',
		'shiptostate'    => 'shiptostate',
		'shiptozip'      => 'shiptozip',
		'phonenbr'       => 'phone',
		'email'          => 'email',
		'shipviacode'    => 'shipmeth',
		'notes'          => 'note',
		'shipcomplete'   => 'shipcom',
		'custpo'         => 'pono',
		'termscodetype'  => 'termtype',
		'paymentmethod'  => 'paytype',
		'cardnumber'     => 'ccno',
		'expiredate'     => 'xpdate',
		'cvc'            => 'vc',
		'ordn'           => 'orders',
	];
	const BOOL_YN_FIELDS = [
		'shipcomplete'
	];
	const OPTIONS_PAYMENTMETHODS = [
		'bill' => 'Bill to Account',
		'cc'   => 'Credit Card',
		'cod'  => 'Collect on Delivery'
	];
	const CREDITCARD_FIELDS = [
		'cardnumber', 'expiredate', 'cvc'
	];

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		foreach (self::FIELDS_STRING as $field) {
			$this->$field = '';
		}
		$this->error = false;
		$this->shipcomplete = true;
		$this->termscodetype = self::DEFAULT_TERMSCODETYPE;
		$this->paymentmethod = self::DEFAULT_PAYMENTMETHOD;
		$this->ordn = 0;
		$this->trackChanges(true);
	}

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set Values from Billing Record
	 * @param  Billing $b
	 * @return void
	 */
	public function setFromBilling(Billing $b) {
		foreach (self::BILLING_KEYMAP as $thisField => $bField) {
			if (in_array($thisField, self::BOOL_YN_FIELDS)) {
				$this->$thisField = $b->$bField == 'Y';
				continue;
			}
			$this->$thisField = $b->$bField;
		}
		$this->error = $b->error == 'Y';

		if ($this->paymentmethod == '') {
			$this->paymentmethod = self::DEFAULT_PAYMENTMETHOD;
		}
		$this->ordn = intval($this->ordn);
		$this->setPaymentFieldsFromBilling($b);
	}

	/**
	 * Set Payment Fields
	 * @param  Billing $b
	 * @return void
	 */
	private function setPaymentFieldsFromBilling(Billing $b) {
		$last4 = $b->cardLast4();
		$this->cclast4 = $last4 ? $last4 : '';

		if (array_key_exists($this->paymentmethod, self::OPTIONS_PAYMENTMETHODS) === false) {
			$this->cardtype = $this->paymentmethod;
			$this->paymentmethod = 'cc';
		}
	}

/* =============================================================
	Getters
============================================================= */
	/**
	 * Return if Record has an Error
	 * @return bool
	 */
	public function hasError() {
		return $this->error;
	}

	/**
	 * Return if Terms Code Type is Standard
	 * @return bool
	 */
	public function hasStdTerms() {
		return $this->termscodetype == self::DEFAULT_TERMSCODETYPE;
	}

	/**
	 * Return if Payment method is Bill-to-Account
	 * @return bool
	 */
	public function isPaymentmethodBill() {
		return $this->paymentmethod == 'bill';
	}

	/**
	 * Return if Payment method is Credit Card
	 * @return bool
	 */
	public function isPaymentmethodCreditCard() {
		return $this->paymentmethod != 'bill' && $this->paymentmethod != 'cod';
	}

	/**
	 * Return if Credit Card is on file
	 * @return bool
	 */
	public function hasCreditCard() {
		return $this->cclast4 != '';
	}
	
}