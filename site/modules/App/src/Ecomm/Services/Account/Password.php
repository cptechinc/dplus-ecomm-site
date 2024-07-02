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
			case 'change-password':
				return $this->processChangePassword($input);
				break;
			case 'forgot-password':
				return $this->processForgotPassword($input);
				break;
		}
	}

	/**
	 * Parse First Change Password, send request
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processFirstChangePassword(WireInputData $input) {
		if ($this->isLoggedIn() === false || $this->isFirstLogin() === false) {
			return false;
		}
		$data = new WireData();
		$data->email	   = $this->table->findOne()->email;
		$data->password    = $input->text('password');
		$data->passwordNew = $input->text('npassword');
		$data->securityAnswer1 = $input->text('securityAnswer1');
		$data->securityAnswer2 = $input->text('securityAnswer2');
		$this->requestFirstChangePassword($data);
		return $this->table->isLoggedIn($this->sessionID);
	}

	/**
	 * Parse Change Password, send request
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processChangePassword(WireInputData $input) {
		if ($this->isLoggedIn() === false || $this->isFirstLogin()) {
			return false;
		}
		$data = new WireData();
		$data->email	   = $this->table->findOne()->email;
		$data->password    = $input->text('password');
		$data->passwordNew = $input->text('npassword');
		$this->requestChangePassword($data);
		return $this->table->isLoggedIn($this->sessionID);
	}

	/**
	 * Parse Forgot Password, send request
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processForgotPassword(WireInputData $input) {
		if ($this->isLoggedIn() || $this->isFirstLogin()) {
			return false;
		}
		$data = new WireData();
		$data->email	   = $input->email('email');
		$data->securityAnswer1 = $input->text('securityAnswer1');
		$data->securityAnswer2 = $input->text('securityAnswer2');
		$this->requestForgotPassword($data);
		return $this->table->hasEmailBeenSent($this->sessionID);
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
			"PASS=$data->password", "NPASS=$data->passwordNew",
			"MMN=$data->securityAnswer1", "CBI=$data->securityAnswer2"
		];
		return $this->writeRqstUpdateDplus($rqst);
	}

	/**
	 * Request password change
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestChangePassword(WireData $data) {
		$rqst = [
			'CHANGE PASS',
			"EMAIL=$data->email",
			"PASS=$data->password", "NPASS=$data->passwordNew",
		];
		return $this->writeRqstUpdateDplus($rqst);
	}

	/**
	 * Request Forgot password
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestForgotPassword(WireData $data) {
		$rqst = [
			'FORGOT PASS',
			"EMAIL=$data->email",
			"MMN=$data->securityAnswer1", "CBI=$data->securityAnswer2"
		];
		return $this->writeRqstUpdateDplus($rqst);
	}
}