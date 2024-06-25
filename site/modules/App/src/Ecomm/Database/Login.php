<?php namespace App\Ecomm\Database;
// Dpluso Model
use LoginQuery as Query, Login as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;

/**
 * Login
 * Reads Records from Login table
 * 
 * @method Query query()
 */
class Login extends AbstractQueryWrapper {
	const MODEL              = 'Login';
	const MODEL_KEY          = 'sessionid';
	const MODEL_TABLE        = 'login';
	const DESCRIPTION        = 'Ecomm session login table';
	const YN_TRUE = 'Y';
	const ERMES_FIRST_LOGIN = 'FIRST LOGIN';

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
	 * Return Login Record for this Session
	 * @return Login|false
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
	 * Return if Session Record is for First Login
	 * @return bool
	 */
	public function isFirstLogin($sessionID) {
		$q = $this->querySession($sessionID);
		$q->filterByValidlogin(self::YN_TRUE);
		$q->filterByErmes(self::ERMES_FIRST_LOGIN);
		return boolval($q->count());
	}
}