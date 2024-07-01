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
 * @static self  $instance
 */
class Login extends AbstractQueryWrapper {
	const MODEL              = 'Login';
	const MODEL_KEY          = 'sessionid';
	const MODEL_TABLE        = 'login';
	const DESCRIPTION        = 'Ecomm session login table';
	const YN_TRUE = 'Y';
	const ERMES_FIRST_LOGIN = 'FIRST LOGIN';
	const ERMES_EMAIL_SENT  = 'SendEmail';
	const VALIDLOGIN_REGISTERED = 'R';

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
	 * Return if Login Record Exists
	 * @param  string $sessionID
	 * @return bool
	 */
	public function exists($sessionID = '') {
		$q = $this->querySession($sessionID);
		return boolval($q->count());
	} 
	
	/**
	 * Return Login Record for this Session
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
	 * Return if Session ID has registered for an account
	 * @return bool
	 */
	public function isRegistered($sessionID = '') {
		$q = $this->querySession($sessionID);
		$q->filterByValidlogin(self::VALIDLOGIN_REGISTERED);
		return boolval($q->count());
	}

	/**
	 * Return if Session Record is for First Login
	 * @return bool
	 */
	public function isFirstLogin($sessionID = '') {
		$q = $this->querySession($sessionID);
		$q->filterByValidlogin(self::YN_TRUE);
		$q->filterByErmes(self::ERMES_FIRST_LOGIN);
		return boolval($q->count());
	}

	/**
	 * Return if Session Record is for First Login
	 * @return bool
	 */
	public function hasEmailBeenSent($sessionID = '') {
		$q = $this->querySession($sessionID);
		$q->filterByErmes(self::ERMES_EMAIL_SENT);
		return boolval($q->count());
	}
}