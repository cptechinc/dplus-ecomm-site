<?php namespace Controllers\Products;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\PaginatedArray;
// App
use App\Ecomm\Search\Pages\ProductsItemGroup as PagesSearcher;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * ItemGroups
 * Handles Search Product ItemGroups Page
 */
class ItemGroups extends AbstractController {
	const SESSION_NS = 'products-item-groups';
	const TEMPLATE   = 'products-item-groups';
	const RESULTS_PERPAGE = 25;

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		$fields = ['q|text'];
		self::sanitizeParametersShort($data, $fields);

		$itemgroups = self::findItemGroups($data);
		return self::display($data, $itemgroups);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return Paginated List of Item Groups
	 * @param  WireData $data
	 * @return PaginatedArray
	 */
	private static function findItemGroups(WireData $data) {
		$PAGES = new PagesSearcher();
		$PAGES->keyword = $data->q;
		return $PAGES->paginate(self::pw('input')->pageNum, self::RESULTS_PERPAGE);
	}

/* =============================================================
	4. URLs
============================================================= */

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data, PaginatedArray $itemgroups) {
		return self::render($data, $itemgroups);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data, PaginatedArray $itemgroups) {
		return self::getTwig()->render('products/item-groups/page.twig', ['itemgroups' => $itemgroups]);
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