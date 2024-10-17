<?php namespace Controllers;
// ProcessWire
use ProcessWire\PageArray;
use ProcessWire\WireData;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * Blog
 * Handles Blog Page
 */
class Blog extends AbstractController {
	const SESSION_NS = 'blog';
	const TEMPLATE   = 'blog';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		self::initPageHooks();

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
	 * Return Posts that have this tag
	 * @param  WireData  $data
	 * @return PageArray(template=blog-post)
	 */
	public static function fetchPosts(WireData $data) {
		$data->pagenbr = $data->pagenbr ? $data->pagenbr : self::getPwInput()->pageNum();
		$data->limit = self::LIMIT_ON_PAGE;
		$data->start = self::getOffsetFromPagenbr($data->pagenbr, self::LIMIT_ON_PAGE);
		$pages = self::getPwPages();
		return $pages->find("template=blog-post,start=$data->start,limit=$data->limit,sort=-blog_date");
	}

/* =============================================================
	4. URLs
============================================================= */
	public static function url() {
		return self::pw('pages')->get('template=blog')->url;
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
		return self::getTwig()->render('blog/page.twig',['posts' => $posts]);
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