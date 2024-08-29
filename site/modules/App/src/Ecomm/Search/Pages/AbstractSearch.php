<?php namespace App\Ecomm\Search\Pages;
// ProcessWire
use ProcessWire\NullPage;
use ProcessWire\Page;
use ProcessWire\PageArray;
use ProcessWire\WireData;

/**
 * AbstractSearch
 * Template class for searching pages
 * 
 * @property string $keyword
 */
abstract class AbstractSearch extends WireData {
	public function __construct() {
		$this->keyword = '';
	}
/* =============================================================
	Public
============================================================= */
	/**
	 * Return Number of Pages that match
	 * @return int
	 */
	public function count() {
		return $this->pages->count($this->selector());
	}

	/**
	 * Return All Search Results
	 * @return PageArray
	 */
	public function find() {
		return $this->pages->find($this->selector());
	}

	/**
	 * Return First Search Result
	 * @return Page|NullPage
	 */
	public function findOne() {
		return $this->pages->get($this->selector());
	}

	/**
	 * Return Paginated Search Results
	 * @param  int    $page  Page to Start on
	 * @param  int    $limit # of Results to Return
	 * @return PageArray
	 */
	public function paginate(int $page = 1, int $limit = 15) {
		return $this->pages->find($this->paginateSelector($page, $limit));
	}

/* =============================================================
	Contracts
============================================================= */
	/**
	 * Return the selector needed to search
	 * @param  string $keyword
	 * @return string
	 */
	abstract public function selector();

/* =============================================================
	Internal
============================================================= */
	/**
	 * Return Selector with Paginated Options
	 * @param  int    $page     Page to Start on
	 * @param  int    $limit    # of Results to Return
	 * @return string
	 */
	public function paginateSelector(int $page = 1, int $limit = 15) {
		$selector = $this->selector();
		$start = 0;
		if ($page > 1) {
			$start = $page * $limit - $limit;
		}
		$selector .= ",start=$start,limit=$limit";
		return $selector;
	}
}