<?php namespace App\Ecomm\Services;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;
// App
use App\Ecomm\Abstracts\Services\AbstractEcommCrudService;
use App\Ecomm\Database\Login as LoginTable;

/**
 * Login
 * Provides Login / Logout Services
 * 
 * @property LoginTable $table
 */
class Login extends AbstractEcommCrudService {
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->table = LoginTable::instance();
	}

/* =============================================================
	Public
============================================================= */
	/**
	 * Parse Login Record into Session
	 * @return bool
	 */
	public function parseLoginIntoSession() {
		$loginRecord = $this->table->findOne($this->sessionID);
		if (empty($loginRecord)) {
			return false;
		}
		$data = new Login\Data\SessionUser();
		$data->setFromLogin($loginRecord);
		$this->session->set('ecuser', $data);
		return true;
	}

	/**
	 * Logout user
	 * @return bool
	 */
	public function logout() {
		if ($this->isLoggedIn() === false) {
			$this->removeLoginFromSession();
			return true;
		}
		if ($this->processLogout() === false) {
			return false;
		}
		$this->removeLoginFromSession();
		return true;
	}

	/**
	 * Remove Login User Data from Session
	 * @return bool
	 */
	private function removeLoginFromSession() {
		$this->session->remove('ecuser');
		return true;
	}

/* =============================================================
	CRUD Reads
============================================================= */
	/**
	 * Return if Session has a record
	 * @return bool
	 */
	public function exists() {
		return $this->table->exists($this->sessionID);
	}

	/**
	 * Return if Session is Logged In
	 * @return bool
	 */
	public function isLoggedIn() {
		return $this->table->isLoggedIn($this->sessionID);
	}

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
			case 'login':
				return $this->processLogin($input);
				break;
			case 'logout':
				return $this->processLogout($input);
		}
	}

	/**
	 * Parse Login, send Request
	 * @param  WireInputData $input
	 * @return bool
	 */
	protected function processLogin(WireInputData $input) {
		$data = new WireData();
		$data->email	= $input->email('email');
		$data->password = $input->text('password');
		$this->requestLogin($data);
		return $this->table->isLoggedIn($this->sessionID);
	}

	/**
	 * Send Logout Request
	 * @param  WireInputData $input
	 * @return bool
	 */
	protected function processLogout(WireInputData $input = null) {
		$data = new WireData();
		$this->requestLogout($data);
		return $this->table->isLoggedIn($this->sessionID) === false;
	}

/* =============================================================
	Dplus Requests
============================================================= */
	/**
	 * Write Login Request File
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestLogin(WireData $data) {
		$rqst = ["LOGIN=$data->email", "PSWD=$data->password"];
		return $this->writeRqstUpdateDplus($rqst);
	}

	/**
	 * Write Logout Request File
	 * @param  WireData $data
	 * @return bool
	 */
	private function requestLogout(WireData $data) {
		return $this->writeRqstUpdateDplus(['LOGOUT']);
	}
}