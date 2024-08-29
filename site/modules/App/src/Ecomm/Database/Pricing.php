<?php namespace App\Ecomm\Database;
// Dpluso Model
use PricingQuery as Query, Pricing as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;

/**
 * Pricing
 * Reads Records from Pricing table
 * 
 * @method Query query()
 * @static self  $instance
 */
class Pricing extends AbstractQueryWrapper {
	const MODEL              = 'Pricing';
	const MODEL_KEY          = 'sessionid,itemid';
	const MODEL_TABLE        = 'pricing';
	const DESCRIPTION        = 'Ecomm session pricing table';

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
	 * Return if Pricing Record(s) Exists
	 * @param  string $sessionID
	 * @return bool
	 */
	public function exist($sessionID = '') {
		$q = $this->querySession($sessionID);
		return boolval($q->count());
	}

	/**
	 * Return if Pricing Record Exists by Item ID
	 * @param  string $sessionID
	 * @param  string $itemID
	 * @return bool
	 */
	public function existsByItemid($sessionID = '', $itemID) {
		$q = $this->querySessionItemid($sessionID, $itemID);
		return boolval($q->count());
	}

	/**
	 * Return Pricing Record
	 * @param  string $sessionID
	 * @param  string $itemID
	 * @return Record
	 */
	public function pricing($sessionID = '', $itemID) {
		$q = $this->querySessionItemid($sessionID, $itemID);
		return $q->findOne();
	}
}