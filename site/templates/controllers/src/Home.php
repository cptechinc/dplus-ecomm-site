<?php namespace Controllers;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\WireData;
// App
use App\Blog\Util\PwSelectors\BlogPost as PostSelectors;
// Controllers
use Controllers\Abstracts\AbstractController;

/**
 * Home
 * Handles Home Page
 */
class Home extends AbstractController {
	const SESSION_NS = 'home';
	const TEMPLATE   = 'home';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		self::initPageHooks();
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
		return self::pw('pages')->get('template=home')->url;
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
		return self::getTwig()->render('home/page.twig');
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
			$event->return = Blog\BlogAuthor::urlAuthor($event->arguments(0));
		});

		$m->addHook("$selector::recentBlogPosts", function(HookEvent $event) {
			$selectors = [
				'template'   => PostSelectors::template(), 
				'pagination' => PostSelectors::pagination(1, 4), 
				'sort'       => PostSelectors::sort(PostSelectors::DEFAULT_SORT)
			];
			$event->return = self::getPwPages()->find(implode(',', array_values($selectors)));
		});
	}
}