<?php namespace Controllers\Blog;
// ProcessWire
use ProcessWire\PageArray;
use ProcessWire\WireArray;
use ProcessWire\WireData;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * BlogTags
 * Handles BlogTags Page
 */
class BlogTags extends AbstractController {
	const SESSION_NS = 'blog-tags';
	const TEMPLATE   = 'blog-tags';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		self::initPageHooks();
		self::getPwPage()->tabtitle = 'Blog Tags';
		self::getPwPage()->tabposttitle = 'Blog';
		$tags = self::fetchTags($data);
		return self::display($data, $tags);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return List of Tags
	 * @param  WireData $data
	 * @return PageArray
	 */
	public static function fetchTags(WireData $data) {
		$pages = self::getPwPages();
		return $pages->find('template=blog-tag, sort=title');
	}

	/**
	 * Return List of Tags grouped by first Letter
	 * @param  WireData  $data
	 * @return WireArray
	 */
	public static function fetchTagsGroupedByFirstLetter(WireData $data) {
		$all = self::fetchTags($data);
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
		return self::pw('pages')->get('template=blog-tags')->url;
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data, PageArray $tags) {
		return self::render($data, $tags);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data, PageArray $tags) {
		return self::getTwig()->render('blog/blog-tags/page.twig', ['tags' => $tags]);
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