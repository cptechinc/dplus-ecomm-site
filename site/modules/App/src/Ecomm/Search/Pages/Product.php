<?php namespace App\Ecomm\Search\Pages;
// App
use App\Ecomm\Util\PwSelectors;

/**
 * Product
 * Fetches Pages that match search criteria and / or have item IDs
 * 
 * @property array $itemIDs
 */
class Product extends AbstractSearch {
	public function __construct() {
		parent::__construct();
		$this->itemIDs = [];
	}

/* =============================================================
	Contracts
============================================================= */
	/**
	 * Return the selector needed to search
	 * @param  string $keyword
	 * @return string
	 */
	public function selector() {
		return PwSelectors\Product::searchWithItemids($this->sanitizer->text($this->keyword), $this->itemIDs);
	}
}