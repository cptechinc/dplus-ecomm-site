<?php namespace Controllers\Ajax\Lookup;
// ProcessWire
use ProcessWire\WireData;
// Controllers
use Controllers\Abstracts\AbstractAjaxLookupController;

/**
 * User
 * Handles User Lookup AJAX Requests
 */
class User extends AbstractAjaxLookupController {
	/**
	 * REturn Rendered HTML for Shipping Addresses lookup
	 * @param  WireData $data
	 * @return string
	 */
	public static function shippingAddresses(WireData $data) {
		return self::_render($data, []);
	}
}