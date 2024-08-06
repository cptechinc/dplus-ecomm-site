<?php namespace Controllers\Products;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\PaginatedArray;
// App
use Dplus\Search\Itemids\Xrefs as ItemidSearcher;
use App\Ecomm\Search\Pages\Product as PagesSearcher;
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
	const RESULTS_PERPAGE = 25;

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
	 * @param  WireData $data
	 * @return PaginatedArray
	 */
	private static function findSearchResults(WireData $data) {
		if (empty($data->q)) {
			return new PaginatedArray();
		}
		$PAGES = new PagesSearcher();
		$PAGES->itemIDs = self::findItemids($data);
		$PAGES->keyword = $data->q;
		return $PAGES->paginate(self::pw('input')->pageNum, self::RESULTS_PERPAGE);
	}

	/**
	 * Return Item IDs that match query
	 * @param  WireData $data
	 * @return array
	 */
	private static function findItemids(WireData $data) {
		$TABLE = ItemidSearcher::instance();
		$TABLE->query = $data->q;
		return $TABLE->find();
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
	
/* =============================================================
	9. Hooks / Object Decorating
============================================================= */
	
}