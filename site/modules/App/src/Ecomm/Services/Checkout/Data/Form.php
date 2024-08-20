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
 * @property string $shiptoname     Ship-To Name
 * @property string $shiptocompany  Ship-To Company
 * @property string $shiptoaddress1 Ship-To Address Line 1
 * @property string $shiptoaddress2 Ship-To Address Line 2
 * @property string $shiptocity     Ship-To Address City
 * @property string $shiptostate    Ship-To Address State
 * @property string $shiptozip      Ship-To Address Zip
 */
class Form extends WireData {
	const FIELDS_STRING = [
		'billtoname', 'billtocompany',
		'billtoaddress1', 'billtoaddress2',
		'billtocity', 'billtostate', 'billtozip',
		'shiptoname', 'shiptocompany',
		'shiptoaddress1', 'shiptoaddress2',
		'shiptocity', 'shiptostate', 'shiptozip',
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
		'shiptoname'     => 'shiptoname',
		'shiptocompany'  => 'shiptocompany',
		'shiptoaddress1' => 'shiptoaddress1',
		'shiptoaddress2' => 'shiptoaddress2',
		'shiptocity'     => 'shiptocity',
		'shiptostate'    => 'shiptostate',
		'shiptozip'      => 'shiptozip',
	];

	public function __construct() {
		foreach (self::FIELDS_STRING as $field) {
			$this->$field = '';
		}
		$this->error = false;
		$this->trackChanges(true);
	}

	/**
	 * Return if Record has an Error
	 * @return bool
	 */
	public function hasError() {
		return $this->error;
	}

	/**
	 * Set Values from Billing Record
	 * @param  Billing $b
	 * @return void
	 */
	public function setFromBilling(Billing $b) {
		foreach (self::BILLING_KEYMAP as $thisField => $bField) {
			$this->$thisField = $b->$bField;
		}
		$this->error = $b->error == 'Y';
	}
}