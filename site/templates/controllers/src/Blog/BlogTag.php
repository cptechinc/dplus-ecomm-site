<?php namespace Controllers\Blog;
// ProcessWire
use ProcessWire\Page;
use ProcessWire\PageArray;
use ProcessWire\WireData;
// App
use App\Blog\Util\PwSelectors\BlogPost as PostSelectors;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * BlogTag
 * Handles blog-tag Page
 */
class BlogTag extends AbstractController {
	const SESSION_NS = 'blog-tag';
	const TEMPLATE   = 'blog-tag';

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
	 * Return Posts that have this tag
	 * @param  WireData  $data
	 * @param  Page|null $page
	 * @return PageArray(template=blog-post)
	 */
	public static function fetchPosts(WireData $data, Page $page = null) {
		if (empty($page)) {
			$page = self::getPwPage();
		}
		$pages = self::getPwPages();
		$selectors = self::generateBlogPostsSelectors($data, $page);
		return $pages->find(implode(',', array_values($selectors)));
	}

/* =============================================================
	4. URLs
============================================================= */
	public static function urlTag($name) {
		return self::pw('pages')->get("template=blog-tag,name=$name")->url;
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
		return self::getTwig()->render('blog/blog-tag/page.twig', ['posts' => $posts]);
	}

/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	8. Supplemental
============================================================= */
	/**
	 * Return Selectors array
	 * @param  WireData $data
	 * @return array
	 */
	private static function generateBlogPostsSelectors(WireData $data, Page $page) {
		$data->pagenbr = $data->pagenbr ? $data->pagenbr : self::getPwInput()->pageNum();
		$data->limit = self::LIMIT_ON_PAGE;
		$selectors = [
			'template'   => PostSelectors::template(), 
			'tag'        => PostSelectors::blogTag($page->name), 
			'pagination' => PostSelectors::pagination($data->pagenbr, $data->limit), 
			'sort'       => PostSelectors::sort(PostSelectors::DEFAULT_SORT)
		];
		return $selectors;
	}

/* =============================================================
	9. Hooks / Object Decorating
============================================================= */
}