<?php namespace App\Ecomm\PageInstallers\Products;
// Propel ORM Library
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as AbstractRecord;
// Dplus Models
use ItemMasterItem as Record;
// ProcessWire
use ProcessWire\Page;
// Dplus
use Dplus\Database\Tables\Item as ItemTable;
// App
use App\Ecomm\Util\PwSelectors\Product as PwSelectors;
use App\Ecomm\PageInstallers\AbstractDplusRecordInstaller;
use App\Ecomm\Pages\Templates\Product as ProductTemplate;
use App\Util\Hashids;


/**
 * Prdoucts\AbstractInstaller
 * Installs Product(s) pages
 * 
 * @static bool installOneFromRecord(Record $r)  Create / Update Page
 * @static bool update(Record $r)                Update Page from Record
 */
class AbstractInstaller extends AbstractDplusRecordInstaller {
	const RECORDSPERLOOP = 100;

/* =============================================================
	Contracts
============================================================= */
	

/* =============================================================
	Creates
============================================================= */
	/**
	 * Create Page from Record
	 * @param  Record $r
	 * @return bool
	 */
	public static function create(AbstractRecord $r) {
		if (parent::create($r) === false) {
			return false;
		}
		$page  = self::page($r->itemid);
		return self::updateHashid($page);
	}

/* =============================================================
	Updates
============================================================= */

	/**
	 * Update Page Name with hashid
	 * @param  Page $p
	 * @return bool
	 */
	public static function updateHashid(Page $p) {
		if ($p->template->name != ProductTemplate::NAME) {
			return false;
		}
		$hashid = Hashids::instance()->encode($p->id);

		if ($p->name == $hashid) {
			return true;
		}
		$p->of(false);
		$p->name = $hashid;
		return $p->save();
	}

/* =============================================================
	Creates / Data Setting
============================================================= */
	/**
	 * Return new Page
	 * @return Page
	 */
	protected static function newPage() {
		$p = new Page();
		$p->template = ProductTemplate::NAME;
		$p->parent   = self::pwPages()->get('template=products');
		return $p;
	}

	/**
	 * Set Page values from Record
	 * @param  Page   $page
	 * @param  Record $r
	 * @return bool
	 */
	protected static function _setData(Page $page, AbstractRecord $r) {
		$page->itemid          = $r->itemid;
		$page->itemdescription = $r->description1 . ' ' . $r->description2;
		$page->title           = $r->itemid;
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
		$TABLE = ItemTable::instance();
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
		$id = $sanitizer->selectorValue($sanitizer->string($id), ['whitelist' => PwSelectors::SELECTOR_ITEMID_WHITELIST]);
		return self::pwPages()->count(PwSelectors::pageByItemid($id)) > 0;
	}

	/**
	 * Return Page
	 * @param  string $id
	 * @return Page
	 */
	public static function page($id) {
		$sanitizer = self::pwSanitizer();
		$id = $sanitizer->selectorValue($sanitizer->string($id), ['whitelist' => PwSelectors::SELECTOR_ITEMID_WHITELIST]);
		return self::pwPages()->get(PwSelectors::pageByItemid($id));
	}

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return Table Filter Data
	 * @return ItemTable\FilterData
	 */
	protected static function generateFilterData() {
		return new ItemTable\FilterData();
	}

	/**
	 * Return Table Filter Data
	 * @return ItemTable
	 */
	protected static function srcTable() {
		return ItemTable::instance();
	}
}

