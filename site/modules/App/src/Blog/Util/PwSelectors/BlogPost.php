<?php namespace App\Blog\Util\PwSelectors;
// App
use Pauldro\ProcessWire\AbstractPwSelectors;

/**
 * BlogPost
 * Class that generates Selector snippets for blog-post pages
 */
class BlogPost extends AbstractPwSelectors {
	const TEMPLATE = 'blog-post';
	const DEFAULT_SORT = '-blog_date';

	/**
	 * Return selector snipper for template
	 * @return string
	 */
	public static function template() {
		return 'template=' . self::TEMPLATE;
	}

	/**
	 * Return snippet for blog category
	 * @param  string $category
	 * @return string
	 */
	public static function blogCategory($category) {
		return "blog_categories=[name=$category]";
	}

	/**
	 * Return snippet for blog tag
	 * @param  string $tag
	 * @return string
	 */
	public static function blogTag($tag) {
		return "blog_tags=[name=$tag]";
	}

	/**
	 * Return snippet for blog author
	 * @param  string $userid
	 * @return string
	 */
	public static function blogAuthor($userid) {
		return "created_users_id=$userid";
	}
}