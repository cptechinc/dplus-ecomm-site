<?php namespace Controllers\Blog;
// ProcessWire
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
		return self::display($data);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */

/* =============================================================
	4. URLs
============================================================= */
	public static function url() {
		return self::pw('pages')->get('template=blog')->url . 'tags/';
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data) {
		return self::render($data);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data) {
		return self::getTwig()->render('blog/blog-tags/page.twig');
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