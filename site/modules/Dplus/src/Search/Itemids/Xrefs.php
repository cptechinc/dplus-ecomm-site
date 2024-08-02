<?php namespace Dplus\Search\Itemids;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\Item\FilterData as ItemFilterData;
use Dplus\Database\Tables;

/**
 * Xrefs
 * Retreives ItemIDs from various sources that match a query
 * 
 * @property string $query     Search Query
 * @property bool   $useItm    Use Item Master Table?
 * @property bool   $useMxrfe  Use Manufacturer X-Ref Table
 */
class Xrefs extends WireData {
	private static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	/**
	 * Return Instance
	 * @return self
	 */
	public static function instance() {
		if (empty(static::$instance)) {
			$instance = new static();
			static::$instance = $instance;
		}
		return static::$instance;
	}

	public function __construct() {
		$this->query    = '';
		$this->useItm   = true;
		$this->useMxrfe = false;
	}

/* =============================================================
	Public
============================================================= */
	/**
	 * Return item IDs found to match search query
	 * @return array
	 */
	public function find() {
		$itemIDs = [];

		if ($this->useItm) {
			$itemIDs = array_merge($itemIDs, $this->findItemidsItm());
		}
		return $itemIDs;
	}

	/**
	 * Return Unique item IDS found for wildcard search
	 * @return array
	 */
	private function findItemidsItm() {
		$data = new ItemFilterData();
		$data->useWildcardSearch = true;
		$data->query = $this->query;
		$TABLE = Tables\Item::instance();
		$results1 = $TABLE->itemidsByWildcardSearch($data);
		$data->useWildcardSearchUppercase = true;
		$results2 = $TABLE->itemidsByWildcardSearch($data);
		$results = array_merge($results1, $results2);
		return array_unique($results);
	}


}