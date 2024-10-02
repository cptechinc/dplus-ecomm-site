<?php namespace App\Ecomm\Services\Dpay;
// ProcessWire
// use ProcessWire\WireData;
use ProcessWire\WireInputData;
// Dpay
use Dpay\Db\Tables\PaymentLinks as PaymentLinksTable;
use Dpay\Db\Tables\Data\PaymentLink as PaymentLinkRecord;
// App
use App\Ecomm\Abstracts\Services\AbstractEcommCrudService;

/**
 * PaymentLinks
 * Provides PaymentLinks Services
 * 
 * @property PaymentLinksTable $table
 */
class PaymentLinks extends AbstractEcommCrudService {
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->table = PaymentLinksTable::instance();
	}

/* =============================================================
	CRUD Reads
============================================================= */
	/**
	 * Return IF Order has a payment link
	 * @param  int $ordn
	 * @return bool
	 */
	public function existsByOrdn($ordn) {
		return $this->table->existsByOrdn($ordn);
	}

	/**
	 * Return payment link record
	 * @param  int $ordn
	 * @return PaymentLinkRecord
	 */
	public function paymentLinkByOrdn($ordn) {
		return $this->table->recordByOrdn($ordn);
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
			// case 'add-to-cart':
			// 	return $this->processAddToCart($input);
			// 	break;
		}
	}
}