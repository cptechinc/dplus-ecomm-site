<?php namespace App\Ecomm\Services;
// Dpluso Model
use Billing as BillingRecord;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;
// Dplus
use Dplus\Database\Tables\CodeTables\Shipvia as ShipviaTable;
// App
use App\Ecomm\Abstracts\Services\AbstractEcommCrudService;
use App\Ecomm\Database\Billing as CheckoutTable;

/**
 * Checkout
 * Provides Checkout Services
 * 
 * @property CheckoutTable $table
 * @property BillingRecord $billing
 */
class Checkout extends AbstractEcommCrudService {
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->table = CheckoutTable::instance();
		$this->billing = null;
	}

/* =============================================================
	Public
============================================================= */
	/**
	 * Initialize Checkout
	 * @return bool
	 */
	public function initCheckout() {
		return $this->processInitCheckout(new WireInputData());
	}

/* =============================================================
	CRUD Reads
============================================================= */
	/**
	 * Return if Checkout record exists
	 * @return bool
	 */
	public function hasCheckout() {
		return $this->table->exists($this->sessionID);
	}

	/**
	 * Return Billing Record
	 * @return BillingRecord
	 */
	public function billing() {
		if (empty($this->billing) === false) {
			return $this->billing;
		}
		$this->billing = $this->billingFromDb();
		$this->billing->setSalt($this->config->billingSalt);
		return $this->billing;
	}

	/**
	 * Return Billing Record
	 * @return BillingRecord
	 */
	public function billingFromDb() {
		return $this->table->findOne($this->sessionID);
	}

	/**
	 * Return Cart Data
	 * @return Checkout\Data\Form
	 */
	public function form() {
		$form = new Checkout\Data\Form();
		$form->setFromBilling($this->billing());
		return $form;
	}

/* =============================================================
	CRUD Processing
============================================================= */
	/**
	 * Process Request
	 * @param  WireInputData $input
	 * @return bool
	 */
	protected function processInput(WireInputData $input) {
		switch ($input->text('action')) {
			case 'init-checkout':
				return $this->processInitCheckout($input);
				break;
			case 'update-address':
				return $this->processUpdateAddress($input);
				break;
			case 'update-payment':
				return $this->processUpdatePayment($input);
				break;
			case 'update-shipping':
				return $this->processUpdateShipping($input);
				break;
		}
	}

	/**
	 * Parse Add to Cart
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processInitCheckout(WireInputData $input) {
		$data = new WireData();
		$this->requestInitCheckout($data);
		return $this->table->exists($this->sessionID);
	}

	/**
	 * Handle Update Address Request
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processUpdateAddress(WireInputData $input) {
		$form = $this->form();
		$form->resetTrackChanges();
		$this->updateFormAddressFields($input, $form);
		return $this->updateBilling($form);
	}

	/**
	 * Handle Update Payment Request
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processUpdatePayment(WireInputData $input) {
		$form = $this->form();
		$form->resetTrackChanges();
		$this->updateFormPaymentFields($input, $form);
		return $this->updateBilling($form);
	}

	/**
	 * Handle Update Shipping Request
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processUpdateShipping(WireInputData $input) {
		$form = $this->form();
		$form->resetTrackChanges();
		$this->updateFormShippingFields($input, $form);
		return $this->updateBilling($form);
	}

/* =============================================================
	Form Updates
============================================================= */
	/**
	 * Update billing / Shipping Address fields
	 * @param  WireInputData      $input
	 * @param  Checkout\Data\Form $form
	 * @return bool
	 */
	private function updateFormAddressFields(WireInputData $input, Checkout\Data\Form $form) {
		$sections = ['billto', 'shipto'];
		$fields   = ['name', 'company', 'address1', 'address2', 'city', 'state', 'zip'];

		foreach ($sections as $prefix) {
			foreach ($fields as $suffix) {
				$field = $prefix . $suffix;
				$form->$field = $input->text($field);
			}
		}
		$form->shiptoid = $input->text('shiptoid');
		return true;
	}

	/**
	 * Update Payment fields
	 * @param  WireInputData      $input
	 * @param  Checkout\Data\Form $form
	 * @return bool
	 */
	private function updateFormPaymentFields(WireInputData $input, Checkout\Data\Form $form) {
		$paymentmethod = $input->text('paymentmethod');

		$isValidPaymentMethod = array_key_exists($paymentmethod, $form::OPTIONS_PAYMENTMETHODS);
		$isValidCreditCard = $this->config->checkout->allowedCreditCards->has($input->text('cardtype'));

		if ($isValidPaymentMethod === false && $isValidCreditCard === false) {
			return false;
		}

		$form->paymentmethod = $paymentmethod;

		if ($form->isPaymentmethodCreditCard() === false) {
			return true;
		}
		if ($input->ynbool('usesavedcard')) {
			return true;
		}
		$form->paymentmethod = $input->text('cardtype');
		$form->cardnumber = $input->text('cardnumber', ['stripSpace' => true]);
		$form->cvc        = $input->text('cvc');
		$form->expiredate = $input->cardDate('expiredate');
		return true;
	}

	/**
	 * Update Shipping fields
	 * @param  WireInputData      $input
	 * @param  Checkout\Data\Form $form
	 * @return bool
	 */
	private function updateFormShippingFields(WireInputData $input, Checkout\Data\Form $form) {
		if ($input->email('email')) {
			$form->email = $input->email('email') ? $input->email('email') : $form->email;
		}
		$form->custpo = $input->string('custpo');
		$form->notes  = $input->textarea('notes');
		$form->shipcomplete = $input->ynbool('shipcomplete');
		$form->phonenbr     = $input->phoneUS('phonenbr');

		$SHIPVIAS = ShipviaTable::instance();
		if ($SHIPVIAS->exists($input->text('shipviacode'))) {
			$form->shipviacode = $input->text('shipviacode');
		}
		return true;
	}

/* =============================================================
	Billing Updates
============================================================= */
	/**
	 * Update Billing Record
	 * NOTE: saves record
	 * @param  Checkout\Data\Form $form
	 * @return bool
	 */
	private function updateBilling(Checkout\Data\Form $form) {
		$changedFields = $form->getChanges();
		$billing = $this->billing();

		foreach ($changedFields as $fieldname) {
			if (array_key_exists($fieldname, $form::BILLING_KEYMAP) === false) {
				continue;
			}
			$field = $form::BILLING_KEYMAP[$fieldname];
			$setField = 'set' . ucfirst($field);
			if (in_array($fieldname, $form::BOOL_YN_FIELDS)) {
				$billing->$setField($form->$fieldname == 'Y');
				continue;
			}
			$billing->$setField($form->$fieldname);
		}
		$saved = $billing->isModified() ? boolval($billing->save()) : true;

		if ($saved === false) {
			return false;
		}

		if (in_array('cardnumber', $changedFields)) {
			$billing->encryptPayment();
		}
		return true;
	}

/* =============================================================
	Dplus Requests
============================================================= */
	/**
	 * Request Init Checkout
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestInitCheckout(WireData $data) {
		$rqst =  ['PREBILL'];
		return $this->writeRqstUpdateDplus($rqst);
	}

}