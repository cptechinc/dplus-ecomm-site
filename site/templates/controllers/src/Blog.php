<?php namespace Controllers;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\PageArray;
use ProcessWire\WireData;
// App
use App\Blog\Util\PwSelectors\BlogPost as PostSelectors;
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
		$selectors = self::generateBlogPostsSelectors($data);
		$pages = self::getPwPages();
		return $pages->find(implode(',', array_values($selectors)));
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
		return self::getTwig()->render('blog/page.twig', ['posts' => $posts]);
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
	private static function generateBlogPostsSelectors(WireData $data) {
		$data->pagenbr = $data->pagenbr ? $data->pagenbr : self::getPwInput()->pageNum();
		$data->limit = self::LIMIT_ON_PAGE;
		$selectors = [
			'template'   => PostSelectors::template(), 
			'pagination' => PostSelectors::pagination($data->pagenbr, $data->limit), 
			'sort'       => PostSelectors::sort(PostSelectors::DEFAULT_SORT)
		];
		return $selectors;
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

		$m->addHook("$selector::authorUrl", function(HookEvent $event) {
			$event->return = Blog\BlogAuthor::urlAuthor($event->arguments(0));
		});
	}

}