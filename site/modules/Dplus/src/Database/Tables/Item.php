<?php namespace Dplus\Database\Tables;
// Propel ORM Library
use Propel\Runtime\Collection\ObjectCollection;
	// use Propel\Runtime\ActiveQuery\ModelCriteria as AbstractQuery;
	// use Propel\Runtime\Util\PropelModelPager;
// Dplus Models
use ItemMasterItemQuery as Query, ItemMasterItem as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;
use Dplus\Abstracts\AbstractFilterData;

/**
 * Item
 * Reads Records from Item table
 * 
 * @method Query query()
 * @method Query queryFilteredByFilterData(Item\FilterData $data)
 * @static self  $instance
 */
class Item extends AbstractQueryWrapper {
	const MODEL              = 'ItemMasterItem';
	const MODEL_KEY          = 'itemid';
	const MODEL_TABLE        = 'inv_item_mast';
	const DESCRIPTION        = 'Dplus Items table';
	const COLUMNS_SEARCH = [
		'itemid',
		'description',
		'description2'
	];

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query Filtered By Order Number
	 * @param  string  $itemID
	 * @return Query
	 */
	public function queryItemid($itemID) {
		return $this->query()->filterByItemid($itemID);
	}

/* =============================================================
	Reads
============================================================= */
	/**
	 * Return if Item Exists
	 * @param  string $itemID
	 * @return bool
	 */
	public function exists($itemID) {
		return boolval($this->queryItemid($itemID)->count());
	}

	/**
	 * Return Item
	 * @param  int $itemID
	 * @return Record
	 */
	public function item($itemID) {
		return $this->queryItemid($itemID)->findOne();
	}

	/**
	 * Return Item IDs that match wildcard search
	 * @param  AbstractFilterData $data
	 * @return array[string]
	 */
	public function itemidsByWildcardSearch(AbstractFilterData $data) {
		$q = $this->queryWildcardSearch($data->query, $data->useWildcardSearchUppercase);
		$q->select(Record::aliasproperty('itemid'));
		return $q->find()->toArray();
	}

	/**
	 * Return Item IDs that have itemgroupcode
	 * @param  string $id
	 * @return array[string]
	 */
	public function itemidsByInvGroup($id) {
		$q = $this->query();
		$q->filterByItemgroup($id);
		$q->select(Record::aliasproperty('itemid'));
		return $q->find()->toArray();
	}

	/**
	 * Return Items that match Item IDs
	 * @param  array $ids
	 * @return ObjectCollection
	 */
	public function findByItemids(array $ids) {
		$q = $this->query();
		$q->filterByItemid($ids);
		return $q->find();
	}

/* =============================================================
	Supplemental
============================================================= */

}