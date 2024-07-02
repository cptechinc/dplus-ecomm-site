<?php namespace App\Configs\Configs;
// ProcessWire
use ProcessWire\WireArray;

/**
 * Register
 * Container for Account Registration Config Data
 * 
 * @property bool      $allowRegister       Allow Account Registration
 * @property bool      $emailRegisters      E-mail Account Registrations?
 * @property string    $emailRegistersFrom  E-mail to E-mail Registration Submits from
 * @property WireArray $emailRegistersTo    E-mail to E-mail Registration Submits To
 */
class Register extends AbstractConfig {
	public function __construct() {
		$this->allowRegister = true;
		$this->emailRegisters  = false;
		$this->emailRegistersFrom  = '';
		$this->emailRegistersTo = new WireArray();
	}
}