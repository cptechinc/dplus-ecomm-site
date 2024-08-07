<?php namespace App\Ecomm\PageInstallers;
// Propel ORM Library
use Propel\Runtime\Util\PropelModelPager;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as AbstractRecord;
// ProcessWire
use ProcessWire\Page;
use ProcessWire\Pages;
// Pauldro\ProcessWire
use Pauldro\ProcessWire\Installers\AbstractStaticPwInstaller;
// Dplus
use Dplus\Abstracts\AbstractFilterData;
use Dplus\Abstracts\AbstractQueryWrapper;



/**
 * AbstractDplusRecordInstaller
 * Template class for Installing PW Pages from Dplus Records
 */
abstract class AbstractDplusRecordInstaller extends AbstractStaticPwInstaller {
	const RECORDSPERLOOP = 100;

/* =============================================================
	Contracts
============================================================= */
	/**
	 * Install PW Components
	 * @return bool
	 */
	protected static function _install() {
		$TABLE = static::srcTable();
		$nbrOfPages   = ceil(static::countSrcRecords() / static::RECORDSPERLOOP);
		$filter = static::generateFilterData();
		$filter->limit = static::RECORDSPERLOOP;
		$results = [];

		for ($i = 1; $i <= $nbrOfPages; $i++) {
			$filter->pagenbr = $i;
			$list = $TABLE->findPaginatedByFilterData($filter);
			$listResults = static::installPropelModelPagerList($list);
			$results = array_merge($results, $listResults);
		}
		static::$results = $results;
		return true;
	}

	/**
	 * Install Pages
	 * @param  PropelModelPager[AbstractRecord] $list
	 * @return array
	 */
	protected static function installPropelModelPagerList(PropelModelPager $list) {
		$results = [];
		
		foreach ($list as $item) {
			$results[$item->id] = static::installOneFromRecord($item);
		}
		return $results;
	}

	/**
	 * Create / Update Page
	 * @param  AbstractRecord $r
	 * @return bool
	 */
	public static function installOneFromRecord(AbstractRecord $r) {
		if (static::exists($r->id)) {
			return static::update($r);
		}
		return static::create($r);
	}

/* =============================================================
	Creates
============================================================= */
	/**
	 * Create Page from Record
	 * @param  AbstractRecord $r
	 * @return bool
	 */
	public static function create(AbstractRecord $r) {
		$page = static::newPage();
		static::setData($page, $r);
		return $page->save();
	}

/* =============================================================
	Updates
============================================================= */
	/**
	 * Update Page for Record
	 * @param  AbstractRecord $r
	 * @return bool
	 */
	public static function update(AbstractRecord $r) {
		$page = static::page($r->id);

		if ($page->id == 0) {
			return static::create($r);
		}
		static::setData($page, $r);
		return $page->save();
	}

/* =============================================================
	Creates / Data Setting
============================================================= */
	/**
	 * Return new Page
	 * @return Page
	 */
	abstract protected static function newPage();

	/**
	 * Set Page values from Record
	 * @param  Page            $page
	 * @param  AbstractRecord  $r
	 * @return bool
	 */
	protected static function setData(Page $page, AbstractRecord $r) {
		return static::_setData($page, $r);
	}

	/**
	 * Set Page values from Record
	 * @param  Page            $page
	 * @param  AbstractRecord  $r
	 * @return bool
	 */
	abstract protected static function _setData(Page $page, AbstractRecord $r);

/* =============================================================
	Data Fetching
============================================================= */
	/**
	 * Return number of source records
	 * @return int
	 */
	abstract protected static function countSrcRecords();

/* =============================================================
	ProcessWire Pages
============================================================= */
	/**
	 * Return that Page exists by identifier
	 * @param  string $id
	 * @return bool
	 */
	protected static function exists($id) {
		return static::_exists($id);
	}

	/**
	 * Return that Page Exists by identifier
	 * @param  string $id
	 * @return bool
	 */
	abstract protected static function _exists($id);

	/**
	 * Return Page
	 * @param  string $id
	 * @return Page
	 */
	abstract protected static function page($id);

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return Pages
	 * @return Pages
	 */
	protected static function pwPages() {
		return static::pw('pages');
	}

	/**
	 * Return Pages
	 * @return Sanitizer
	 */
	protected static function pwSanitizer() {
		return static::pw('sanitizer');
	}

	/**
	 * Return Table Filter Data
	 * @return AbstractFilterData
	 */
	abstract protected static function generateFilterData();

	/**
	 * Return Table
	 * @return AbstractQueryWrapper
	 */
	abstract protected static function srcTable();
}

