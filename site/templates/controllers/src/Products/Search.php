<?php namespace Controllers\Products;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\PaginatedArray;
// App
use App\Ecomm\Search\Pages\Product as PagesSearcher;
use App\Ecomm\Search\Products\XrefKeyTable;
use App\Ecomm\Services\Product\Pricing;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * ProductSearch
 * Handles Search Product Page
 */
class Search extends AbstractController {
	const SESSION_NS = 'products-search';
	const TEMPLATE   = 'products-search';
	const RESULTS_PERPAGE = 24;

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		$fields = ['q|text'];
		self::sanitizeParametersShort($data, $fields);

		if ($data->q) {
			self::pw('page')->headline = "Search: $data->q";
		}

		$results = self::findSearchResults($data);
		Pricing::instance()->sendRequestForMultiple($results->explode('itemid'));
		self::appendJs($data);
		return self::display($data, $results);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return Paginated List of Results
	 * NOTE: keep public for AJAX
	 * @param  WireData $data
	 * @return PaginatedArray
	 */
	public static function findSearchResults(WireData $data) {
		if (empty($data->q)) {
			return new PaginatedArray();
		}
		$itemids = self::findItemids($data);
		$PAGES = new PagesSearcher();
		$PAGES->itemIDs = $itemids->getArray();

		$results = new PaginatedArray();
		$results->setArray($PAGES->find());
		$results->setTotal($itemids->getTotal());
		$results->setLimit($itemids->getLimit());
		return $results;
	}

	/**
	 * Return Item IDs that match query
	 * @param  WireData $data
	 * @return PaginatedArray
	 */
	private static function findItemids(WireData $data) {
		$TABLE = XrefKeyTable::instance();
		$results = $TABLE->itemidsByWildcardSearchPaginated($data->q, self::getPwInput()->inputNum, self::RESULTS_PERPAGE);
		$page  = self::getPwInput()->pageNum;
		$limit = self::RESULTS_PERPAGE;

		$list = new PaginatedArray();
        $list->setStart($page == 1 ? 0 : ($page * $limit) - $limit);
        $list->setLimit($limit);
        $list->setTotal($results->getNbResults());
        $list->setArray($results->toArray());
		return $list;
	}

	private static function requestPricing(array $itemIDs) {
		$PRICING = Pricing::instance();
		$PRICING->sendRequestForMultiple($itemIDs);
	}

/* =============================================================
	4. URLs
============================================================= */

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data, PaginatedArray $results) {
		return self::render($data, $results);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data, PaginatedArray $results) {
		return self::getTwig()->render('products/search/page.twig', ['results' => $results]);
	}

/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	8. Supplemental
============================================================= */
	/**
	 * Return List of Script filepaths to be appended
	 * @param  WireData $data
	 * @return array
	 */
	protected static function getJsScriptPaths(WireData $data) {
		$jsPath = 'scripts/pages/products/search/';
		$filenames = ['classes/Requests.js', 'page.js'];
		$scripts = [];

		foreach ($filenames as $filename) {
			$scripts[] = $jsPath . $filename;
		}
		return $scripts;
	}

	protected static function appendJs(WireData $data, $scripts = []) {
		self::appendJsJqueryValiudate();

		$scripts = self::getJsScriptPaths($data);
		parent::appendJs($data, $scripts);
	}
	
/* =============================================================
	9. Hooks / Object Decorating
============================================================= */
	
}