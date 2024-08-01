<?php namespace App\Util\Data;
// ProcessWire
use ProcessWire\WireData;

/**
 * JsonResultData
 * Container for JSON Request Result Data
 * 
 * @property bool   $success 
 * @property bool   $error    
 * @property string $msg      Result Message
 * @property string $action   Request Action
 */
class JsonResultData extends WireData {
	public function __construct() {
		$this->success = false;
		$this->error   = false;
		$this->msg     = '';
		$this->action  = '';
	}
}