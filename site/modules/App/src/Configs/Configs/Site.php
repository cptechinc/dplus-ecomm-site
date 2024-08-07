<?php namespace App\Configs\Configs;
// ProcessWire
use ProcessWire\WireArray;

/**
 * Site
 * Container for Site Config
 * 
 * @property bool    $useTopbar          Use Topbar
 * @property bool    $showProductPages   Make Product Pages Accessible?
 * @property string  $productImportFlag  Flag / Marker to find products from ITM
 * @property bool    $showInStock        Show product is in Stock?
 * @property bool    $showQtyInStock     Show Numeric Qty in Stock?
 */
class Site extends AbstractConfig {
	public function __construct() {
		$this->useTopbar = true;
		$this->showProductPages = false;
		$this->productImportFlag =  '';
		$this->showInStock       = true;
		$this->showQtyInStock    = true;
	}
}