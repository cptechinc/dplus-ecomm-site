<?php namespace Controllers\Blog;
// ProcessWire
use ProcessWire\User;
use ProcessWire\WireData;


/**
 * BlogAuthorPosts
 * Handles blog author's posts Page
 */
class BlogAuthorPosts extends BlogAuthor {
	const SESSION_NS = 'blog-author-posts';
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
		self::getPwPage()->tabtitle = "$author->title's Posts";
		self::getPwPage()->title = "$author->title's Posts";
		self::getPwPage()->tabposttitle = 'Blog';
		return self::display($data, $author);
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
	

/* =============================================================
	5. Displays
============================================================= */
	
/* =============================================================
	6. HTML Rendering
============================================================= */
	protected static function render(WireData $data, User $author) {
		return self::getTwig()->render('blog/blog-author-posts/page.twig', ['author' => $author]);
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