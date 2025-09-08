<?php namespace Controllers\Ajax\Json;
// ProcessWire
use ProcessWire\NullPage;
use ProcessWire\Page;
use ProcessWire\WireData;
use App\Ecomm\Search\Products\XrefKeyTable;
// App
use App\Util\Data\JsonResultData as ResultData;
use App\Ecomm\Search\Pages\Product as PagesSearcher;
// Controllers
use Controllers\Abstracts\AbstractJsonController;

/**
 * Products
 * 
 * Handles Products AJAX Requests
 */
class Products extends AbstractJsonController {
/* =============================================================
	1. Indexes
============================================================= */
	public static function validateItemid(WireData $data) {
		$fields = ['itemID|string', 'jqv|bool'];
		self::sanitizeParametersShort($data, $fields);

		$data->action = 'validate-itemid';

		$result = self::createResultData($data);

		if (empty($data->itemID)) {
			self::setResultFailure($result, "Item ID was not provided");
			return $data->jqv ? $result->msg : false;
		}

		$page = self::fetchProductPageByItemid($data);

		if ($page->id == 0) {
			self::setResultFailure($result, "'$data->itemID' not found");
			return $data->jqv ? $result->msg : false;
		}
		$result->success = true;
		return true;
	}

	public static function getProductByItemid(WireData $data) {
		$fields = ['itemID|string', 'jqv|bool'];
		self::sanitizeParametersShort($data, $fields);

		$result = self::createResultData($data);

		if (empty($data->itemID)) {
			self::setResultFailure($result, "Item ID was not provided");
			return $result->data;
		}

		$page = self::fetchProductPageByItemid($data);

		if ($page->id == 0) {
			self::setResultFailure($result, "'$data->itemID' not found");
			return $result->data;
		}
		$result->success = true;
		$pData = self::parsePageData($page);
		$result->product = $pData->data;
		return $result->data;
	}

	public static function getProductByXrefKey(WireData $data) {
		$fields = ['q|string', 'jqv|bool'];
		self::sanitizeParametersShort($data, $fields);

		$result = self::createResultData($data);

		if (empty($data->q)) {
			self::setResultFailure($result, "Search was not provided");
			return $result->data;
		}
		$TABLE = XrefKeyTable::instance();
		$itemids = $TABLE->itemidsByWildcardSearchPaginated($data->q, 1, 1);

		if ($itemids->getNbResults()) {
			if (empty($data->q)) {
				self::setResultFailure($result, "Not found");
				return $result->data;
			}
		}
		$data->itemID =  $itemids->toArray()[0];
		return self::getProductByItemid($data);
	}

/* =============================================================
	3. Data Fetching / Requests / Retrieval / Processing
============================================================= */
	/**
	 * Return Product Page by Item ID
	 * @param  WireData $data
	 * @return Page|NullPage
	 */
	private static function fetchProductPageByItemid(WireData $data) {
		$PAGES = self::createPageSearcher($data, [$data->itemID]);
		return $PAGES->findOne();
	}

	/**
	 * Set Result Failure
	 * @param  ResultData $result
	 * @param  string     $msg
	 * @return void
	 */
	private static function setResultFailure(ResultData $result, $msg = '') {
		$result->success = false;
		$result->error   = true;
		$result->msg     = $msg;
	}

	/**
	 * Return Product Page Data
	 * @param  Page $page
	 * @return WireData
	 */
	private static function parsePageData(Page $page) {
		$pData = new WireData();
		$pData->itemid = $page->itemid;
		$pData->description = $page->itemdescription;
		$pData->itemdescription = $page->itemdescription;
		$pData->listprice       = $page->listprice;
		$pData->sellprice       = $page->sellprice;
		$pData->qtyInStock      = $page->qtyInStock;
		$pData->pricebreaks     = $page->pricebreaks;
		return $pData;
	}

/* =============================================================
	8. Supplemental
============================================================= */
	/**
	 * Return PagesSearcher
	 * @param  WireData $data
	 * @param  array    $itemIDs
	 * @return PagesSearcher
	 */
	private static function createPageSearcher(WireData $data, $itemIDs = []) {
		$PAGES = new PagesSearcher();
		$PAGES->itemIDs = $itemIDs;
		return $PAGES;
	}

	/**
	 * Create Result Data
	 * @param  WireData $data
	 * @return ResultData
	 */
	private static function createResultData(WireData $data) {
		$result = new ResultData();
		if ($data->has('action')) {
			$result->action  = $data->action;
		}
		if ($data->has('itemID')) {
			$result->itemID  = $data->itemID;
		}
		return $result;
	}
}