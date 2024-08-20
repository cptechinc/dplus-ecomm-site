<?php namespace App\Ecomm\Services;
// Propel ORM Library
use Propel\Runtime\Collection\ObjectCollection;
// Dpluso Model
use Billing as Record;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;
// App
use App\Ecomm\Abstracts\Services\AbstractEcommCrudService;
use App\Ecomm\Database\Billing as CheckoutTable;

/**
 * Checkout
 * Provides Checkout Services
 * 
 * @property CheckoutTable $table
 */
class Checkout extends AbstractEcommCrudService {
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->table = CheckoutTable::instance();
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
	 * Return Cart Data
	 * @return Checkout\Data\Form
	 */
	public function form() {
		$form = new Checkout\Data\Form();
		$form->setFromBilling($this->table->findOne($this->sessionID));
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