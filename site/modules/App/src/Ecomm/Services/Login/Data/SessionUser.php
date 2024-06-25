<?php namespace App\Ecomm\Services\Login\Data;
// Dpluso Models
use Login as LoginRecord;
// ProcessWire
use ProcessWire\WireData;

/**
 * SessionUser
 * Container for Ecomm User Data
 * 
 * @property string $sessionid	Session ID
 * @property string $name		User's Name
 * @property string $email		User's Email
 * @property string $custid 	Customer ID
 * @property string $shiptoid	Customer Ship-to ID
 * @property string $custname	Customer Name
 */
class SessionUser extends WireData {
	const FIELDS_STRING = [
		'sessionid', 'name', 'email',
		'custid', 'shiptoid', 'custname',
	];

	const FIELD_MAP = [
		'name'	   => 'contact',
		'custname' => 'name'
	];

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		$this->sessionid = '';
		$this->name      = '';
		$this->email     = '';
		$this->custid	 = '';
		$this->shiptoid  = '';
		$this->custname  = '';
	}

/* =============================================================
	2. Setters
============================================================= */
	/**
	 * Set Fields From Login Record
	 * @param  LoginRecord $r
	 * @return bool
	 */
	public function setFromLogin(LoginRecord $r) {
		foreach (self::FIELDS_STRING as $field) {
			if (array_key_exists($field, self::FIELD_MAP)) {
				$fieldAlias = self::FIELD_MAP[$field];
				$this->field = $r->$fieldAlias;
				continue;
			}
			$this->$field = $r->$field;
		}
		return true;
	}
}