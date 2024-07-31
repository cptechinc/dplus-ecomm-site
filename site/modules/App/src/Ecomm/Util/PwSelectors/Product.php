<?php namespace App\Ecomm\Util\PwSelectors;
// App
use App\Ecomm\Pages\Templates\Product as ProductTemplate;

/**
 * Product
 * Class that generates Selector String for Product Pages
 */
class Product extends AbstractPwSelectors {
	const TEMPLATE = ProductTemplate::NAME;
	const SELECTOR_ITEMID_WHITELIST = ['#'];

	/**
	 * Return selector for a Product Page By Itemid
	 * @param  string $itemID
	 * @return string
	 */
	public static function pageByItemid($itemID) {
		$t = self::TEMPLATE;
		return "template=$t,itemid=$itemID";
	}
}