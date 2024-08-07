<?php namespace App\Ecomm\Search\Pages;
// App
use App\Ecomm\Util\PwSelectors;

/**
 * ProductsItemGroup
 * Fetches Products Item Group Pages that match search criteria
 */
class ProductsItemGroup extends AbstractSearch {

/* =============================================================
	Contracts
============================================================= */
	/**
	 * Return the selector needed to search
	 * @param  string $keyword
	 * @return string
	 */
	public function selector() {
		return PwSelectors\ProductsItemGroup::search($this->sanitizer->text($this->keyword));
	}
}