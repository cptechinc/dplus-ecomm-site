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

	/**
	 * Return Selector for Product Pages that have item IDs and or match search
	 * @param  string $search
	 * @param  array  $itemIDs
	 * @return string
	 */
	public static function searchWithItemids($search = '', $itemIDs = []) {
		if (empty($search) && empty($itemIDs)) {
			return '';
		}
		if (empty($search)) {
			return self::itemids($itemIDs);
		}
		$selector = self::selectorInParens(self::search($search));
		$selector .= self::selectorInParens(self::itemids($itemIDs));
		return $selector;
	}

	/**
	 * Return selector for a Product Page By Itemid
	 * @param  string $search
	 * @return string
	 */
	public static function search($search) {
		$t = self::TEMPLATE;
		return "template=$t,itemid|itemdescription|title%=$search";
	}
	
	/**
	 * Return Selector pages with Item IDs
	 * @param  array $itemIDs
	 * @return string
	 */
	public static function itemids(array $itemIDs) {
		$t = self::TEMPLATE;
		$itemids = implode('|', $itemIDs);
		// TODO: sanitize each element for selector value
		// $itemids = self::pwSanitizer()->selectorValue($itemids);
		return "template=$t,itemid=$itemids";
	}
}