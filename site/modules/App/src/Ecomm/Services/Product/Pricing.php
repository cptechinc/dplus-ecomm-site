<?php namespace App\Ecomm\Services\Product;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;
// App
use App\Ecomm\Abstracts\Services\AbstractEcommCrudService;
use App\Ecomm\Database\Pricing as PricingTable;

/**
 * Pricing
 * Provides Pricing Services
 * 
 * @property PricingTable $table
 */
class Pricing extends AbstractEcommCrudService {
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->table = PricingTable::instance();
	}

/* =============================================================
	Public
============================================================= */
	/**
	 * Parse, Send Request for Pricing of one Item
	 * @param  string $itemID
	 * @return bool
	 */
	public function sendRequestForOne($itemID) {
		return $this->sendRequestForMultiple([$itemID]);
	}

	/**
	 * Parse, Send Request for Pricing of multiple Items
	 * @param  array $itemIDs
	 * @return bool
	 */
	public function sendRequestForMultiple($itemIDs = []) {
		$input = new WireInputData();
		$input->itemIDs = $itemIDs;
		return $this->processPriceRequest($input);
	}

/* =============================================================
	CRUD Reads
============================================================= */
	/**
	 * Return if Session has a record
	 * @return bool
	 */
	public function exist() {
		return $this->table->exist($this->sessionID);
	}

	/**
	 * Return if Session has pricing record for Item ID
	 * @param  string $itemID
	 * @return bool
	 */
	public function exists($itemID) {
		return $this->table->existsByItemid($this->sessionID, $itemID);
	}

	/**
	 * Return pricing record for ItemID
	 * @return bool
	 */
	public function pricing($itemID) {
		return $this->table->pricing($this->sessionID, $itemID);
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
			case 'request-price':
				return $this->processPriceRequest($input);
				break;
		}
	}

	/**
	 * Parse Pricing, send Request
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processPriceRequest(WireInputData $input) {
		$data = new WireData();
		$data->itemIDs = $input->array('itemIDs');
		$this->requestPrices($data);
		return $this->exist();
	}

/* =============================================================
	Dplus Requests
============================================================= */
	/**
	 * Write Pricing Request File
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestPrices(WireData $data) {
		$rqst = ['ITMPRIMULT'];
		foreach ($data->itemIDs as $itemID) {
			$rqst[] = "ITEMID=$itemID";
		}
		return $this->writeRqstUpdateDplus($rqst);
	}
}