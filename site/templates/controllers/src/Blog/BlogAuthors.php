<?php namespace Controllers\Blog;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\PageArray;
use ProcessWire\WireData;
use ProcessWire\WireArray;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * BlogAuthors
 * Handles blog-authors page
 */
class BlogAuthors extends AbstractController {
	const SESSION_NS = 'blog-authors';
	const TEMPLATE   = 'blog-authors';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		self::initPageHooks();
		self::getPwPage()->tabtitle = 'Blog Authors';
		
		$authors = self::fetchAuthorsGroupedByFirstLetter($data);
		return self::display($data, $authors);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */
	
/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return List of Authors
	 * @param  WireData $data
	 * @return PageArray
	 */
	private static function fetchAuthors(WireData $data) {
		$users = self::getPwUsers();
		$authorRole = self::getPwRoles()->get('blog-author');
		return $users->find("roles=$authorRole, sort=title");
	}

	/**
	 * Return List of Authors grouped by first Letter
	 * @param  WireData $data
	 * @return WireArray
	 */
	private static function fetchAuthorsGroupedByFirstLetter(WireData $data) {
		$all = self::fetchAuthors($data);
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
		return self::pw('pages')->get('template=blog-authors')->url;
	}

	public static function urlAuthor($slug) {
		return BlogAuthor::urlAuthor($slug);
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
		return self::getTwig()->render('blog/blog-authors/page.twig', ['authors' => $list]);
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
		$selector = static::getPageHooksTemplateSelector();
		$m = self::pw('modules')->get('App');
		
		$m->addHook("$selector::authorUrl", function(HookEvent $event) {
			$event->return = self::urlAuthor($event->arguments(0));
		});
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