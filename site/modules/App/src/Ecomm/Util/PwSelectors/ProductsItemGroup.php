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
	 * Return selector for a Products Item Group Page By Dplusid
	 * @param  string $id
	 * @return string
	 */
	public static function pageByDplusid($id) {
		$t = self::TEMPLATE;
		return "template=$t,dplusid=$id";
	}

	/**
	 * Return selector for Products Item Group pages search
	 * @param  string $search
	 * @return string
	 */
	public static function search($search = '') {
		$t = self::TEMPLATE;
		if ($t == '') {
			return "template=$t";
		}
		return "template=$t,dplusid|dplusdescription|title%=$search";
	}
}