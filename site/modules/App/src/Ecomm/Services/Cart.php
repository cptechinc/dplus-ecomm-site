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

	/**
	 * Remove Item from Cart
	 * @param  string  $itemID
	 * @param  int     $qty
	 * @return bool
	 */	
	public function removeFromCart($itemID) {
		$input = new WireInputData();
		$input->itemID = $itemID;
		return $this->processRemoveToCart($input);	
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

	/**
	 * Return the Qty for Item ID
	 * @param  string $itemID
	 * @return float
	 */
	public function itemidQty($itemID) {
		return $this->table->itemidQty($this->sessionID, $itemID);
	}

	/**
	 * Return the number of cart items
	 * @return int
	 */
	public function countItems() {
		return $this->table->countItems($this->sessionID);
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
			case 'remove-from-cart':
				return $this->processRemoveFromCart($input);
				break;
			case 'update-item-qty':
				return $this->processUpdateItemQty($input);
				break;
		}
	}

	/**
	 * Parse Add to Cart
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processAddToCart(WireInputData $input) {
		$data = new WireData();
		$data->itemID = $input->string('itemID');
		$data->qty    = $input->int('qty', ['min' => 1]);
		$beforeQty = $this->itemidQty($data->itemID);
		$data->qty += $beforeQty;
		$this->requestAddToCart($data);
		$afterQty = $this->itemidQty($data->itemID);
		return $afterQty > $beforeQty;
	}

	/**
	 * Parse Remove From Cart
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processRemoveFromCart(WireInputData $input) {
		$data = new WireData();
		$data->itemID = $input->string('itemID');
		$this->requestRemoveFromCart($data);
		return $this->exists($data->itemID) === false;
	}

	/**
	 * Parse Update Item's Qty
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processUpdateItemQty(WireInputData $input) {
		$data = new WireData();
		$data->itemID = $input->string('itemID');
		$data->qty    = $input->int('qty', ['min' => 1]);
		$this->requestUpdateItemQty($data);
		$afterQty = $this->itemidQty($data->itemID);
		return $afterQty == $data->qty;
	}

/* =============================================================
	Dplus Requests
============================================================= */
	/**
	 * Request Add Item to Cart
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestAddToCart(WireData $data) {
		$rqst =  ['ADDTOCART', "ITEMID=$data->itemID", "QTY=$data->qty"];
		return $this->writeRqstUpdateDplus($rqst);
	}

	/**
	 * Request Remove Item from Cart
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestRemoveFromCart(WireData $data) {
		$rqst =  ['ADDTOCART', "ITEMID=$data->itemID", "QTY=0"];
		return $this->writeRqstUpdateDplus($rqst);
	}

	/**
	 * Request Item Qty Update
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestUpdateItemQty(WireData $data) {
		$rqst =  ['ADDTOCART', "ITEMID=$data->itemID", "QTY=$data->qty"];
		return $this->writeRqstUpdateDplus($rqst);
	}
}