<?php namespace App\Ecomm\PageInstallers\Products\ItemGroups;
// Propel ORM Library
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as AbstractRecord;
// Dplus Models
use ItemMasterItem as Record;
// ProcessWire
use ProcessWire\Page;
// Dplus
use Dplus\Database\Tables\CodeTables\FilterData;
use Dplus\Database\Tables\CodeTables\ItemGroup as ItemGroupTable;
// App
use App\Ecomm\Util\PwSelectors\ProductsItemGroup as PwSelectors;
use App\Ecomm\PageInstallers\AbstractDplusRecordInstaller;
use App\Ecomm\Pages\Templates\ProductsItemGroup as Template;
use App\Util\Hashids;


/**
 * Products\Installer
 * Installs Product(s) pages
 * 
 * @static bool installOneFromRecord(Record $r)  Create / Update Page
 * @static bool update(Record $r)                Update Page from Record
 */
class Installer extends AbstractDplusRecordInstaller {
	const RECORDSPERLOOP = 100;

/* =============================================================
	Contracts
============================================================= */

/* =============================================================
	Creates
============================================================= */

/* =============================================================
	Updates
============================================================= */

/* =============================================================
	Creates / Data Setting
============================================================= */
	/**
	 * Return new Page
	 * @return Page
	 */
	protected static function newPage() {
		$p = new Page();
		$p->template = Template::NAME;
		$p->parent   = self::pwPages()->get('template=products-item-groups');
		return $p;
	}

	/**
	 * Set Page values from Record
	 * @param  Page   $page
	 * @param  Record $r
	 * @return bool
	 */
	protected static function _setData(Page $page, AbstractRecord $r) {
		$page->dplusid          = $r->code;
		$page->dplusdescription = $r->description;
		$page->title            = $r->code;
		$page->name             = self::pwSanitizer()->pageName($r->code);
		return true;
	}

/* =============================================================
	Data Fetching
============================================================= */
	/**
	 * Return number of source records
	 * @return int
	 */
	protected static function countSrcRecords() {
		$TABLE = ItemGroupTable::instance();
		return  $TABLE->countAll();
	}

/* =============================================================
	ProcessWire Pages
============================================================= */
	/**
	 * Return that Page Exists by identifier
	 * @param  string $id
	 * @return bool
	 */
	protected static function _exists($id) {
		$sanitizer = self::pwSanitizer();
		$id = $sanitizer->selectorValue($sanitizer->string($id), ['whitelist' => PwSelectors::SELECTOR_DPLUSID_WHITELIST]);
		return self::pwPages()->count(PwSelectors::pageByDplusid($id)) > 0;
	}

	/**
	 * Return Page
	 * @param  string $id
	 * @return Page
	 */
	public static function page($id) {
		$sanitizer = self::pwSanitizer();
		$id = $sanitizer->selectorValue($sanitizer->string($id), ['whitelist' => PwSelectors::SELECTOR_DPLUSID_WHITELIST]);
		return self::pwPages()->get(PwSelectors::pageByDplusid($id));
	}

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return Table Filter Data
	 * @return FilterData
	 */
	protected static function generateFilterData() {
		return new FilterData();
	}

	/**
	 * Return Table Filter Data
	 * @return ItemGroupTable
	 */
	protected static function srcTable() {
		return ItemGroupTable::instance();
	}
}

