<?php namespace Controllers\Ajax\Json;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Util\Data\JsonResultData as ResultData;
use App\Ecomm\Search\Pages\Product as PagesSearcher;
// Controllers
use Controllers\Abstracts\AbstractJsonController;

/**
 * Products
 * Handles Products AJAX Requests
 */
class Products extends AbstractJsonController {
/* =============================================================
	1. Indexes
============================================================= */
	public static function validateItemid(WireData $data) {
		$fields = ['itemID|string', 'jqv|bool'];
		self::sanitizeParametersShort($data, $fields);

		$result = new ResultData();
		$result->action  = $data->action;
		$result->itemID  = $data->itemID;
		$result->qty     = $data->qty;

		if (empty($data->itemID)) {
			$result->error   = true;
			$result->msg     = "Item ID was not provided";
			return $data->jqv ? $result->msg : false;
		}

		$PAGES = new PagesSearcher();
		$PAGES->itemIDs = [$data->itemID];
		$result->success = $PAGES->count() > 0;
		$result->error   = $result->success === false;
		$result->msg     = $result->error ? "$data->itemID not found" : '';

		if ($result->success) {
			return true;
		}
		return $data->jqv ? $result->msg : false;
	}
}