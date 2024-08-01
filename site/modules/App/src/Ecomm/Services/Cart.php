<?php namespace App\Ecomm\Services;
// Propel ORM Library
use Propel\Runtime\Collection\ObjectCollection;
// Dpluso Model
use Cart as Record;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;
// App
use App\Ecomm\Abstracts\Services\AbstractEcommCrudService;
use App\Ecomm\Database\Cart as CartTable;

/**
 * Cart
 * Provides Cart Services
 * 
 * @property CartTable $table
 */
class Cart extends AbstractEcommCrudService {
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->table = CartTable::instance();
	}

/* =============================================================
	Public
============================================================= */
	/**
	 * Add Item To Cart
	 * @param  string  $itemID
	 * @param  int     $qty
	 * @return bool
	 */	
	public function addToCart($itemID, $qty = 1) {
		$input = new WireInputData();
		$input->itemID = $itemID;
		$input->qty = $qty;
		return $this->processAddToCart($input);	
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
	 * Return if Session has cart record for Item ID
	 * @param  string $itemID
	 * @return bool
	 */
	public function exists($itemID) {
		return $this->table->existsByItemid($this->sessionID, $itemID);
	}

	/**
	 * Return all Cart Records
	 * @return ObjectCollection[Record]
	 */
	public function items() {
		return $this->table->all($this->sessionID);
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
			case 'add-to-cart':
				return $this->processAddToCart($input);
				break;
		}
	}

	/**
	 * Parse Cart, send Request
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processAddToCart(WireInputData $input) {
		$data = new WireData();
		$data->itemID = $input->string('itemID');
		$data->qty    = $input->int('qty', ['min' => 1]);
		$this->requestAddToCart($data);
		return $this->exist();
	}

/* =============================================================
	Dplus Requests
============================================================= */
	/**
	 * Write Add to Cart Request File
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestAddToCart(WireData $data) {
		$rqst =  ['ADDTOCART', "ITEMID=$data->itemID", "QTY=$data->qty"];
		return $this->writeRqstUpdateDplus($rqst);
	}
}