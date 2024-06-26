<?php namespace App\Ecomm\Services\Account;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;
// App
use App\Ecomm\Database\Login as LoginTable;
use App\Ecomm\Services\Login as LoginService;

/**
 * Login
 * Provides Login / Logout Services
 * 
 * @property LoginTable $table
 */
class Password extends LoginService {
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
			case 'first-change-password':
				return $this->processFirstChangePassword($input);
				break;
		}
	}

	/**
	 * Parse Login, send Request
	 * @param  WireInputData $input
	 * @return bool
	 */
	protected function processFirstChangePassword(WireInputData $input) {
		if ($this->isLoggedIn() === false || $this->isFirstLogin() === false) {
			return false;
		}
		$data = new WireData();
		$data->email	   = $input->email('email');
		$data->password    = $input->text('password');
		$data->passwordNew = $input->text('passwordNew');
		$data->securityAnswer1 = $input->text('securityAnswer1');
		$data->securityAnswer2 = $input->text('securityAnswer2');
		$this->requestFirstChangePassword($data);
		return $this->table->isLoggedIn($this->sessionID);
	}

/* =============================================================
	Dplus Requests
============================================================= */
	/**
	 * Request First password change
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestFirstChangePassword(WireData $data) {
		$rqst = [
			'FIRST CHANGE PASS',
			"EMAIL=$data->email",
			"PSWD=$data->password", "NPASS=$data->passwordNew",
			"MMN=$data->securityAnswer1", "CBI=$data->securityAnswer2"
		];
		return $this->writeRqstUpdateDplus($rqst);
	}
}