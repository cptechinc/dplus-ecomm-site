<?php namespace App\Ecomm\Abstracts\Services;
// Propel ORM Library
	// use Propel\Runtime\ActiveRecord\ActiveRecordInterface as Record;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Connectors\Dpluso as DbDpluso;
// App
use App\AbstractCrudManager;
use App\Ecomm\Util\CgiRequest;

/**
 * AbstractEcommCrudManager
 * CRUD Manager for Sending Requests to Dplus
 * 
 * @property string  $sessionID  Session ID
 */
abstract class AbstractEcommCrudService extends AbstractCrudManager {
	public function __construct() {
		$this->sessionID = session_id();
	}

/* =============================================================
	Dplus Requests
============================================================= */
	/**
	 * Return Request Array with DB prepended
	 * @param  array $rqst
	 * @return array
	 */
	protected function prependDbToRqst($rqst = []) {
		$dplusdb = DbDpluso::instance()->dbconfig->dbName;
		return array_merge(["DBNAME=$dplusdb"], $rqst);
	}

	/**
	 * Write Request File
	 * @param  array $rqst
	 * @return bool
	 */
	protected function writeRqstFile($rqst = []) {
		return CgiRequest::writeFile($this->prependDbToRqst($rqst), $this->sessionID);
	}

	/**
	 * Sends Update Request
	 * @return bool
	 */
	protected function updateDplus() {
		$config = $this->wire('config');
		return CgiRequest::send($config->cgis['ecomm'], $this->sessionID);
	}

	/**
	 * Write Request File, Send Update to Dplus
	 * @param  array $rqst
	 * @return bool
	 */
	protected function writeRqstUpdateDplus($rqst = []) {
		$this->writeRqstFile($rqst);
		$this->updateDplus();
	}
}