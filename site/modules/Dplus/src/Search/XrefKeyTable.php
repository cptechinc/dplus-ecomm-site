<?php namespace Dplus\Search;
// Propel ORM
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Util\PropelModelPager;
// Dplus Models
use ItemXrefKeyQuery as Query, ItemXrefKey as Record;
// Dplus
use Dplus\Database\Tables\ItemXrefKey;

/**
 * Query Wrapper for searching the ItemXrefKey Table
 */
class XrefKeyTable extends ItemXrefKey {
    const COLUMNS_SEARCH = [
		'xitemid',
		'description1',
		'description2',
	];

    protected static $instance;

/* =============================================================
	Query Functions
============================================================= */
    /**
     * Return Query Filtered By sources
     * @return Query
     */
    public function querySources() {
       return $this->query()->filterByRkeysource([0,2]);
    }

	/**
     * Return Query Filtered By sources
     * @return Query
     */
	public function queryWildcardSearchNew($query, $useUpperCase = true) {
		$q = $this->querySources();
		$keyword = $this->wildcardify($query);
		$keyword = $useUpperCase ? strtoupper($keyword) : $keyword;
		$col = $q->tablecolumn(Record::aliasproperty('xitemid'));
		return $q->where("$col LIKE ?", $keyword);
	}

	/**
	 * Return Query with LIKE conditions
	 * @param  string  $query         Search String
	 * @param  bool    $useUpperCase
	 * @return Query
	 */
	public function queryWildcardSearch($query, $useUpperCase = true) {
		$q = $this->querySources();
		$keyword = $this->wildcardify($query);
		$keyword = $useUpperCase ? strtoupper($keyword) : $keyword;
		$class = $this->newRecord();

		$colMap = $this->tableMapColumns($q, $class);
		$this->addColumnLikeConditions($q, $keyword, $colMap, $useUpperCase);
		$this->addConcatenatedColumnsLikeCondition($q, $keyword, $colMap, $useUpperCase);

		$conditions = static::COLUMNS_SEARCH;
		$conditions[] = 'concat';
		$q->where($conditions, Criteria::LOGICAL_OR);
		return $q;
	}

/* =============================================================
	Reads
============================================================= */
    /**
	 * Return Item IDs that match wildcard search
	 * @param  string $q
	 * @return array[string]
	 */
	public function itemidsByWildcardSearch($q) {
		$q = $this->queryWildcardSearch($q, true);
		$q->select(Record::aliasproperty('itemid'));
		return $q->find()->toArray();
	}

	/**
	 * Return list of itemids
	 * @param  string $q
	 * @param  int $page
	 * @param  int $limit
	 * @return PropelModelPager
	 */
	public function itemidsByWildcardSearchPaginated($q, $page = 1, $limit = 15) {
		$q = $this->queryWildcardSearch($q, true);
		$q->select(Record::aliasproperty('itemid'));
		return $q->paginate($page, $limit);
	}
}