<?php namespace Controllers\Blog;
// ProcessWire
use ProcessWire\PageArray;
use ProcessWire\WireData;
use ProcessWire\WireArray;
// Controllers
use Controllers\Abstracts\AbstractController;


/**
 * BlogCategories
 * Handles BlogCategories Page
 */
class BlogCategories extends AbstractController {
	const SESSION_NS = 'blog-categories';
	const TEMPLATE   = 'blog-categories';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		self::initPageHooks();
		self::getPwPage()->tabtitle = 'Blog Categories';

		$categories = self::fetchCategoriesGroupedByFirstLetter($data);
		return self::display($data, $categories);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return List of Categories
	 * @param  WireData $data
	 * @return PageArray
	 */
	public static function fetchCategories(WireData $data) {
		$pages = self::getPwPages();
		return $pages->find('template=blog-category, sort=title');
	}

	/**
	 * Return List of Categories grouped by first Letter
	 * @param  WireData $data
	 * @return WireArray
	 */
	public static function fetchCategoriesGroupedByFirstLetter(WireData $data) {
		$all = self::fetchCategories($data);
		$list = new WireArray();

		foreach ($all as $category) {
			$first = substr($category->title, 0, 1);

			if ($list->has($first)) {
				/** @var WireArray */
				$sublist = $list->get($first);
				$sublist->add($category);
				continue;
			}
			$sublist = new WireArray();
			$sublist->add($category);
			$list->set($first, $sublist);
		}
		return $list;
	}

/* =============================================================
	4. URLs
============================================================= */
	public static function url() {
		return self::pw('pages')->get('template=blog')->url . 'categories/';
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data, WireArray $list) {
		return self::render($data, $list);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data, WireArray $list) {
		return self::getTwig()->render('blog/blog-categories/page.twig', ['categories' => $list]);
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
	/**
	 * Initialze Page Hooks
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPageHooks($tplname = '') {
		// $selector = static::getPageHooksTemplateSelector();
		// $m = self::pw('modules')->get('App');
	}

	/**
	 * Add Hooks to Pages
	 * @param  string $tplname
	 * @return bool
	 */
	public static function initPagesHooks() {
		// $m = self::pw('modules')->get('App');
	}
}