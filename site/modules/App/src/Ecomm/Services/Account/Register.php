<?php namespace App\Ecomm\Services\Account;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;
// App
use App\Ecomm\Database\Login as LoginTable;
use App\Ecomm\Services\Login as LoginService;

/**
 * Register
 * Provides Account Registration Services
 * 
 * @property LoginTable $table
 */
class Register extends LoginService {
	protected static $instance;

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
			case 'register':
				return $this->processRegister($input);
				break;
		}
	}

	/**
	 * Parse First Change Password, send request
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processRegister(WireInputData $input) {
		if ($this->isLoggedIn() === false || $this->isFirstLogin() === false) {
			return false;
		}
		$data = new WireData();
		$data->email	   = $input->email('email');
		$data->contact     = $input->text('contact');
		$this->requestRegister($data);
		return $this->table->isLoggedIn($this->sessionID);
	}

/* =============================================================
	Dplus Requests
============================================================= */
	/**
	 * Request Register
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestRegister(WireData $data) {
		$rqst = ['EXTCUST', "EMAIL=$data->email", "CONTACT=$data->contact"];
		return $this->writeRqstUpdateDplus($rqst);
	}
}