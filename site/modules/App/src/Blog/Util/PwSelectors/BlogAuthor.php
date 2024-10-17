<?php namespace App\Blog\Util\PwSelectors;
// App
use Pauldro\ProcessWire\AbstractPwSelectors;

/**
 * BlogAuthor
 * Class that generates Selector snippets for blog-author user
 */
class BlogAuthor extends AbstractPwSelectors {
	const ROLE = 'blog-author';
	const DEFAULT_SORT = 'title';

	/**
	 * Return selector snippet for role
	 * @return string
	 */
	public static function role() {
		return 'roles=' . self::ROLE;
	}
}