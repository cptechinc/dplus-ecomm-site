<?php namespace Controllers\Blog;
// ProcessWire
use ProcessWire\Page;
use ProcessWire\PageArray;
use ProcessWire\WireData;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * BlogCategory
 * Handles BlogCategory Page
 */
class BlogCategory extends AbstractController {
	const SESSION_NS = 'blog-category';
	const TEMPLATE   = 'blog-category';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		self::initPageHooks();
		self::getPwPage()->tabposttitle = 'Blog';

		$posts = self::fetchPosts($data);
		return self::display($data, $posts);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return Posts that have this category
	 * @param  Page|null $page
	 * @param  WireData  $data
	 * @return PageArray(template=blog-post)
	 */
	public static function fetchPosts(WireData $data, Page $page = null) {
		if (empty($page)) {
			$page = self::getPwPage();
		}
		$data->limit = self::LIMIT_ON_PAGE;
		$data->start = self::getOffsetFromPagenbr(self::getPwInput()->pageNum(), self::LIMIT_ON_PAGE);
		$pages = self::getPwPages();
		return $pages->find("template=blog-post,start=$data->start,limit=$data->limit,blog_categories=[name=$page->name]");
	}

/* =============================================================
	4. URLs
============================================================= */
	public static function urlCategory($name) {
		return self::pw('pages')->get("template=blog-category,name=$name")->url;
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data, PageArray $posts) {
		return self::render($data, $posts);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data, PageArray $posts) {
		return self::getTwig()->render('blog/blog-category/page.twig', ['posts' => $posts]);
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