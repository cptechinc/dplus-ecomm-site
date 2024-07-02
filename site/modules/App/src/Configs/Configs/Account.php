<?php namespace App\Configs\Configs;
// ProcessWire
use ProcessWire\WireArray;

/**
 * Account
 * Container for Account Config
 * 
 * @property string  $labelSecurityQuestion1  Text for Security Question 1
 * @property string  $labelSecurityQuestion2  Text for Security Question 2
 */
class Account extends AbstractConfig {
	public function __construct() {
		$this->labelSecurityQuestion1 = "Mother's Maiden Name";
		$this->labelSecurityQuestion2 = "City Born In";
	}
}