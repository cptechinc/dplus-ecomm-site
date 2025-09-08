<?php namespace Controllers\Products;
// ProcessWire
use ProcessWire\Page;
use ProcessWire\PageArray;
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\Item as ItemTable;
// App
use App\Ecomm\Search\Pages\Product as PagesSearcher;
use App\Ecomm\Services\Product\Pricing;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * Products\ItemGroup
 * Handles Product Item Group Pages
 */
class ItemGroup extends AbstractController {
	const SESSION_NS = 'products-item-group';
	const TEMPLATE	 = 'products-item-group';
	const RESULTS_PERPAGE = 24;

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		self::initPageHooks();
		self::appendJs($data);
		$products = self::findProductPages($data);
		return self::display($data, $products);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return ItemIDs that are associated with ItemGroup
	 * @param  WireData $data
	 * @return array
	 */
	private static function findItemids(WireData $data) {
		return ItemTable::instance()->itemidsByInvGroup(self::getPwPage()->dplusid);
	}

	/**
	 * Return Product Pages assoicated with ItemGroup
	 * @param  WireData $data
	 * @param  array	$itemIDs
	 * @return PageArray[Page]
	 */
	private static function findProductPages(WireData $data) {
		$PAGES = new PagesSearcher();
		$PAGES->itemIDs = self::findItemids($data);
		if (empty($PAGES->itemIDs)) {
			return new PageArray();
		}
		self::requestPricing($PAGES->itemIDs);
		return $PAGES->paginate(self::pw('input')->pageNum, self::RESULTS_PERPAGE);
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
	private static function display(WireData $data, PageArray $products) {
		return self::render($data, $products);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data, PageArray $products) {
		return self::getTwig()->render('products/item-groups/group/page.twig', ['products' => $products]);
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
		$jsPath = 'scripts/pages/products/item-groups/group/';
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
	/**
	 * Initialze Page Hooks
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPageHooks($tplname = '') {
		$selector = static::getPageHooksTemplateSelector();
		$m = self::pw('modules')->get('App');

		// $m->addHook("$selector::forgotPasswordUrl", function($event) {
		//	$event->return = AccountController\ForgotPassword::url();
		// });

		// $m->addHook("$selector::registerUrl", function($event) {
		//	$event->return = AccountController\Register::url();
		// });
	}

	/**
	 * Add Hooks to Pages
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPagesHooks() {
		$m = self::pw('modules')->get('App');

		// $m->addHook("Pages::logoutUrl", function($event) {
		//	$event->return = self::logoutUrl($event->arguments(0));
		// });
	}
}