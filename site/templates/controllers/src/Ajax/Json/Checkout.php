<?php namespace Controllers\Ajax\Json;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Services\Checkout as Service;
use App\Util\Data\JsonResultData as ResultData;
// Controllers
use Controllers\Abstracts\AbstractJsonController;
use Controllers\Checkout as CheckoutController;

/**
 * Checkout
 * Handles Checkout AJAX Requests
 */
class Checkout extends AbstractJsonController {
	const SESSION_NS = CheckoutController::SESSION_NS;

/* =============================================================
	1. Indexes
============================================================= */
	public static function process(WireData $data) {
		$fields = ['action|text'];
		self::sanitizeParametersShort($data, $fields);
		$result = new ResultData();
		$result->action = $data->action;
	
		if (empty($data->action)) {
			$result->error   = true;
			$result->msg     = "Invalid Action";
			return $result->data;
		}

		$updated = '';

		switch($data->action) {
			case 'update-address':
				$updated = 'address';
				break;
		}

		$CHECKOUT = Service::instance();
		$result->success = $CHECKOUT->process($data);
		$result->error   = $result->success === false;
		$result->msg     = $result->error ? "$updated fields not updated" : "updated $updated fields";
		$data->success   = $result->success;
		CheckoutController::updateCompletedStepsAfterProcessing($data);
		$result->steps = self::getSessionVar('steps');
		return $result->data;
	}
}