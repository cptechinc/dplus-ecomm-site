<?php namespace App\Ecomm\Services\Account;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;
use ProcessWire\WireMail;
// App
use App\Configs\Configs\Register as Config;
use App\Ecomm\Database\Login as LoginTable;
use App\Ecomm\Services\Login as LoginService;

/**
 * Register
 * Provides Account Registration Services
 * 
 * @property LoginTable $table
 */
class Register extends LoginService {
	const SESSION_NS ='register';

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
	 * Parse Register, send request
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processRegister(WireInputData $input) {
		if ($this->isLoggedIn() || $this->isFirstLogin()) {
			return false;
		}
		$data = new WireData();
		$data->contact     = $input->text('contact');
		$data->companyname = $input->text('companyname');
		$data->email	   = $input->email('email');
		$data->phone       = $input->text('phone');
		$data->address1    = $input->text('address1');
		$data->address2    = $input->text('address2');
		$data->city        = $input->text('city');
		$data->state       = $input->text('state');
		$data->zip         = $input->text('zip');
		$this->requestRegister($data);
		$success = $this->table->isRegistered($this->sessionID);

		if ($success === false) {
			return false;
		}
		$this->setSessionVar('emailsent', $data->email);
		return $this->emailRegistration($data);
	}

	/**
	 * Email Registration Data
	 * @param  WireData $data
	 * @return bool
	 */
	private function emailRegistration(WireData $data) {
		/** @var Config */
		$config = $this->config->register;
		
		if ($config->allowRegister === false || $config->emailRegistersTo->count() == 0) {
			return false;
		}

		$body = ['Requested Account:', "EMAIL: $data->email", "COMPANY: $data->companyname",
			"CONTACT: $data->contact", "PHONE: $data->phone",  "ADDRESS: $data->address1", "ADDRESS2: $data->address2",
			"CITY: $data->city", "STATE: $data->state", "ZIP: $data->zip",
		];

		foreach ($config->emailRegistersTo as $address) {
			$email = $this->mail->new();
			
			$email->subject("Account Registration: $data->companyname")
			->to($address)
			->from($config->emailRegistersFrom)
			->body(implode("\r\n", $body));
			$numSent = $email->send();
		}
		return true;
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