<?php namespace App\Ecomm\Database;
// Propel ORM Library
use Propel\Runtime\Collection\ObjectCollection;
// Dpluso Model
use CartQuery as Query, Cart as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;

/**
 * Cart
 * Reads Records from Cart table
 * 
 * @method Query query()
 * @static self  $instance
 */
class Cart extends AbstractQueryWrapper {
	const MODEL 			 = 'Cart';
	const MODEL_KEY 		 = 'sessionid,itemid';
	const MODEL_TABLE		 = 'pricing';
	const DESCRIPTION		 = 'Ecomm session pricing table';
	const YN_TRUE = 'Y';

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return query filtered by Session ID
	 * @param  string $sessionID
	 * @return Query
	 */
	public function querySession($sessionID = '') {
		$sessionID = $sessionID ? $sessionID : session_id();
		$q = $this->query();
		$q->filterBySessionid($sessionID);
		return $q;
	}

	/**
	 * Return query filtered by Session ID, item ID
	 * @param  string $sessionID
	 * @param  string $itemID
	 * @return Query
	 */
	public function querySessionItemid($sessionID = '', $itemID) {
		$q = $this->querySession($sessionID);
		$q->filterByItemid($itemID);
		return $q;
	}
	
/* =============================================================
	Reads
============================================================= */
	/**
	 * Return if Cart Record(s) Exists
	 * @param  string $sessionID
	 * @return bool
	 */
	public function exist($sessionID = '') {
		$q = $this->querySession($sessionID);
		return boolval($q->count());
	}

	/**
	 * Return if Cart Record Exists by Item ID
	 * @param  string $sessionID
	 * @param  string $itemID
	 * @return bool
	 */
	public function existsByItemid($sessionID = '', $itemID) {
		$q = $this->querySessionItemid($sessionID, $itemID);
		return boolval($q->count());
	}

	/**
	 * Return Qty for Item ID
	 * @param  string $sessionID
	 * @param  string $itemID
	 * @return bool
	 */
	public function itemidQty($sessionID = '', $itemID) {
		$q = $this->querySessionItemid($sessionID, $itemID);
		$q->withColumn('SUM(qty)', 'total');
		$q->select('total');
		return floatval($q->findOne());
	}

	/**
	 * Return Cart Records
	 * @param  string $sessionID
	 * @param  string $itemID
	 * @return ObjectCollection[Record]
	 */
	public function all($sessionID = '') {
		$q = $this->querySession($sessionID);
		$q->filterByItemid('', '!=');
		return $q->find();
	}

	/**
	 * Return Number of Items In cart
	 * @param  string $sessionID
	 * @return int
	 */
	public function countItems($sessionID = '') {
		$q = $this->querySession($sessionID);
		$q->filterByItemid('', '!=');
		return $q->count();
	}

	/**
	 * Return Tax Amount
	 * @param  string $sessionID
	 * @return float
	 */
	public function taxAmount($sessionID = '') {
		$q = $this->querySession($sessionID);
		$q->filterByDesc1('TAX');
		$q->select('amount');
		return floatval($q->findOne());
	}

	/**
	 * Return Subtotal
	 * @param  string $sessionID
	 * @return float
	 */
	public function subtotal($sessionID = '') {
		$q = $this->querySession($sessionID);
		$q->filterByDesc1('ITEM SUBTOTAL');
		$q->select('amount');
		return floatval($q->findOne());
	}

	/**
	 * Return Shipping Cost
	 * @param  string $sessionID
	 * @return float
	 */
	public function shipping($sessionID = '') {
		$q = $this->querySession($sessionID);
		$q->filterByDesc1('FREIGHT');
		$q->filterByAmount('99999.99', '!=');
		$q->select('amount');
		return floatval($q->findOne());
	}
}