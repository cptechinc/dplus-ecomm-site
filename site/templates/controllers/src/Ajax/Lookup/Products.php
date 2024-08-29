<?php namespace Controllers\Ajax\Lookup;
// ProcessWire
use ProcessWire\WireData;
// Controllers
use Controllers\Abstracts\AbstractAjaxLookupController; 
use Controllers\Products\Search as SearchController;

/**
 * Products
 * Handles Products Lookup AJAX Requests
 */
class Products extends AbstractAjaxLookupController {
	/**
	 * Return Rendered HTML for add-to-cart product lookup
	 * @param  WireData $data
	 * @return string
	 */
	public static function addToCart(WireData $data) {
		self::sanitizeParametersShort($data, ['q|text']);
		$results = SearchController::findSearchResults($data);
		return self::_render($data, ['results' => $results]);
	}
}