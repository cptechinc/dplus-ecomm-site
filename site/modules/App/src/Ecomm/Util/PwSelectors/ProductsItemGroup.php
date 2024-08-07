<?php namespace App\Ecomm\Util\PwSelectors;
// App
use App\Ecomm\Pages\Templates\ProductsItemGroup as Template;

/**
 * ProductsItemGroup
 * Class that generates Selector String for Products Item Group Pages
 */
class ProductsItemGroup extends AbstractPwSelectors {
	const TEMPLATE = Template::NAME;
	const SELECTOR_DPLUSID_WHITELIST = ['#'];

	/**
	 * Return selector for a  Products Item Group Page By Dplusid
	 * @param  string $id
	 * @return string
	 */
	public static function pageByDplusid($id) {
		$t = self::TEMPLATE;
		return "template=$t,dplusid=$id";
	}
}