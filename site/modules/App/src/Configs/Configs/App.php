<?php namespace App\Configs\Configs;
// ProcessWire
use ProcessWire\WireArray;
// App
use App\Pw\Roles;

/**
 * App
 * Container for Application Config Data
 * 
 * @property bool $requireLogin             Require Login to view Site?
 * @property bool $useDpay                  Use Dpay?
 * @property bool $allowOrdering            Allow User to Order Items
 * @property bool $requireLoginToOrder      Require Login to Order Items
 * @property bool $showOpenInvoices         Show Open Invoices
 */
class App extends AbstractConfig {
	public function __construct() {
		$this->requireLogin  = false;
		$this->useDpay       = false;
		$this->allowOrdering = false;
		$this->requireLoginToOrder = false;
		$this->showOpenInvoices = true;
	}
}