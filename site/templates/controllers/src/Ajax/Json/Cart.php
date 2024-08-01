<?php namespace Controllers\Ajax\Json;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Cart as Service;
use App\Util\Data\JsonResultData as ResultData;
// Controllers
use Controllers\Abstracts\AbstractJsonController;

/**
 * Cart
 * Handles CART AJAX Requests
 */
class Cart extends AbstractJsonController {
/* =============================================================
	1. Indexes
============================================================= */
	public static function addToCart(WireData $data) {
		$fields = ['action|text', 'itemID|string', 'qty|int'];
		self::sanitizeParametersShort($data, $fields);
		$result = new ResultData();
		$result->action = $data->action;
		$result->itemID  = $data->itemID;
		$result->qty     = $data->qty;

		if (empty($data->itemID)) {
			$result->error   = true;
			$result->msg     = "Item ID was not provided";
			return $result->data;
		}

		$CART = Service::instance();
		$result->success = $CART->addToCart($data->itemID, $data->qty);
		$result->error   = $result->success === false;
		$result->msg     = $result->error ? "$data->itemID not added" : '';
		return $result->data;
	}
}