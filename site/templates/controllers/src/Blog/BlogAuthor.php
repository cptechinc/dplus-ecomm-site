<?php namespace Controllers\Blog;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\PageArray;
use ProcessWire\User;
use ProcessWire\WireData;
use ProcessWire\WireArray;
// Controllers
use Controllers\Abstracts\AbstractController;


/**
 * BlogAuthors
 * Handles blog author Page
 */
class BlogAuthor extends AbstractController {
	const SESSION_NS = 'blog-author';
	const TEMPLATE   = 'blog-authors';

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		self::sanitizeParametersShort($data, ['name|pageName']);
		
		if ($data->name) {
			return self::author($data);
		}
		self::getPwSession()->redirect(BlogAuthors::url(), $http301=false);
	}

	public static function author(WireData $data) {
		$author = self::fetchAuthor($data);
		
		if (empty($author)) {
			self::getPwSession()->redirect(BlogAuthors::url(), $http301=false);
			return false;
		}
		$author->posts = self::fetchPosts($data, $author);

		self::initPageHooks();
		self::getPwPage()->tabtitle = $author->title;
		self::getPwPage()->title = "About $author->title";
		self::getPwPage()->tabposttitle = 'Blog';
		return self::display($data, $author);
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */
	
/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return Author
	 * @param  WireData $data
	 * @return User|false
	 */
	private static function fetchAuthor(WireData $data) {
		$users = self::getPwUsers();
		$authorRole = self::getPwRoles()->get('blog-author');
		$user = $users->get("roles=$authorRole,name=$data->name");
		return $user->id ? $user : false;
	}

	/**
	 * Return Posts written by User
	 * @param  WireData  $data
	 * @param  User      $user
	 * @return PageArray(template=blog-post)
	 */
	private static function fetchPosts(WireData $data, User $user) {
		$data->limit = self::LIMIT_ON_PAGE;
		$data->start = self::getOffsetFromPagenbr(self::getPwInput()->pageNum(), self::LIMIT_ON_PAGE);
		$pages = self::getPwPages();
		return $pages->find("template=blog-post,start=$data->start,limit=$data->limit,created_users_id=$user->id,sort=-created");
	}

/* =============================================================
	4. URLs
============================================================= */
	public static function url() {
		return BlogAuthors::url();
	}

	public static function urlAuthor($slug) {
		$name = self::getPwSanitizer()->pageName($slug);
		return self::url() . "$name/";
	}

	public static function urlAuthorPosts($slug) {
		return self::urlAuthor($slug) .  'posts/';
	}

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data, User $author) {
		return self::render($data, $author);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data, User $author) {
		return self::getTwig()->render('blog/blog-author/page.twig', ['author' => $author]);
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
		
		$m->addHook("$selector::authorPostsUrl", function(HookEvent $event) {
			$event->return = self::urlAuthorPosts($event->arguments(0));
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