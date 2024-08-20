<?php namespace App\Ecomm\Database;
// Dpluso Model
use BillingQuery as Query, Billing as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;

/**
 * Billing
 * Reads Records from Billing table
 * 
 * @method Query query()
 * @static self  $instance
 */
class Billing extends AbstractQueryWrapper {
	const MODEL              = 'Billing';
	const MODEL_KEY          = 'sessionid';
	const MODEL_TABLE        = 'billing';
	const DESCRIPTION        = 'Ecomm session Checkout table';
	const YN_TRUE = 'Y';

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return query filtered by session ID
	 * @param  string $sessionID
	 * @return Query
	 */
	public function querySession($sessionID = '') {
		$sessionID = $sessionID ? $sessionID : session_id();
		$q = $this->query();
		$q->filterBySessionid($sessionID);
		return $q;
	}
	
/* =============================================================
	Reads
============================================================= */
	/**
	 * Return if Billing Record Exists
	 * @param  string $sessionID
	 * @return bool
	 */
	public function exists($sessionID = '') {
		$q = $this->querySession($sessionID);
		return boolval($q->count());
	} 
	
	/**
	 * Return Billing Record for this Session
	 * @return Record|false
	 */
	public function findOne($sessionID = '') {
		$q = $this->querySession($sessionID);
		return $q->findOne();
	}

	/**
	 * Return if Session ID has a login record
	 * @return bool
	 */
	public function isLoggedIn($sessionID = '') {
		$q = $this->querySession($sessionID);
		$q->filterByValidlogin(self::YN_TRUE);
		return boolval($q->count());
	}

	/**
	 * Return if Record has Error
	 * @return bool
	 */
	public function hasError($sessionID = '') {
		$q = $this->querySession($sessionID);
		$q->filterByError(self::YN_TRUE);
		return boolval($q->count());
	}

/* =============================================================
	Deletes
============================================================= */
	/**
	 * Removes Record for Session
	 * @param  string $sessonID
	 * @return bool
	 */
	public function removeBilling($sessionID = '') {
		$q = $this->querySession($sessionID);
		return boolval($q->delete());
	}
}