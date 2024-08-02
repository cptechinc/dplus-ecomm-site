<?php namespace Dplus\Abstracts;
// Propel Classes
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria as Query;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as Record;
	// use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Util\PropelModelPager;
// ProcessWire
use ProcessWire\WireData;

/**
 * AbstractQueryWrapper
 * Template for querying records from database
 */
abstract class AbstractQueryWrapper extends WireData {
	const MODEL              = '';
	const MODEL_KEY          = '';
	const MODEL_TABLE        = '';
	const DESCRIPTION        = '';
	const SORT_OPTIONS = ['ASC', 'DESC'];
	const WILDCARD_CHAR = '%';
	const COLUMNS_SEARCH = [
		'itemid',
		'description',
		'description2'
	];

	protected static $instance;
	
/* =============================================================
	Constructors
============================================================= */
	/** @return static */
	public static function instance() {
		if (empty(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query Class Name
	 * @return string
	 */
	public function queryClassName() {
		return $this::MODEL.'Query';
	}

	/**
	 * Return model Class Name
	 * @return string
	 */
	public function modelClassName() {
		return static::MODEL;
	}

	/**
	 * Return Model
	 * @return Record
	 */
	public function newModel() {
		$class = $this->modelClassName();
		return new $class();
	}

	/**
	 * Return Model
	 * @return Record
	 */
	public function newRecord() {
		$class = $this->modelClassName();
		return new $class();
	}

	/**
	 * Return New Query Class
	 * @return Query
	 */
	public function getQueryClass() {
		$class = static::queryClassName();
		return $class::create();
	}

	/**
	 * Returns the associated Query class for table code
	 * @return mixed
	 */
	public function query() {
		return $this->getQueryClass();
	}

	/**
	 * Return Query Filtered By Filter Data
	 * @param  AbstractFilterData $data
	 * @return Query
	 */
	public function queryFilteredByFilterData(AbstractFilterData $data) {
		if ($data->useWildcardSearch) {
			return $this->queryWildcardSearch($data->query, $data->useWildcardSearchUppercase);
		}
		return $this->query();
	}

	/**
	 * Apply Order By Clause to Query
	 * @param  Query              $q
	 * @param  AbstractFilterData $data
	 * @return bool
	 */
	public function applyOrderByFilterData(Query $q, AbstractFilterData $data) {
		$model = $this->modelClassName();

		if (empty($data->sortby) === false && $model::aliasproperty_exists($data->sortby)) {
			$col = $model::aliasproperty($data->sortby);
			$q->orderBy($col, $data->sortdir);
			return true;
		}
		if (empty($data::DEFAULT_SORTBY)) {
			return true;
		}
		$col = $model::aliasproperty($data::DEFAULT_SORTBY);
		$q->orderBy($col, $data::DEFAULT_SORTDIR);
		return true;
	}

	/**
	 * Return Query with LIKE conditions
	 * @param  string  $query         Search String
	 * @param  bool    $useUpperCase
	 * @return Query
	 */
	public function queryWildcardSearch($query, $useUpperCase = false) {
		$q = $this->query();
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

	/**
	 * Add Like Conditions for each Column to Query
	 * @param  Query   $q
	 * @param  string  $keyword
	 * @param  array   $colMap
	 * @param  boolean $useUpperCase
	 * @return bool
	 */
	protected function addColumnLikeConditions(Query $q, $keyword, array $colMap, $useUpperCase = false) {
		foreach (static::COLUMNS_SEARCH as $alias) {
			$col = $colMap[$alias];
			$col = $useUpperCase ? "UPPER($col)" : $col;
			$q->condition($alias, "$col LIKE ?", $keyword);
		}
		return true;
	}
	
	/**
	 * Add Like Condition for a concatenated column set
	 * @param  Query   $q
	 * @param  string  $keyword
	 * @param  array   $colMap
	 * @param  boolean $useUpperCase
	 * @return bool
	 */
	protected function addConcatenatedColumnsLikeCondition(Query $q, $keyword, array $colMap, $useUpperCase = false) {
		$columnsConcat = implode(", ' ', " , array_values($colMap));
		$concat = $useUpperCase ? "CONCAT($columnsConcat)" : "UPPER(CONCAT($columnsConcat))";
		$q->condition('concat', "$concat LIKE ?", $keyword);
		return true;
	}
	
/* =============================================================
	Reads
============================================================= */
	/**
	 * Return Results Paginated
	 * @param  AbstractFilterData $data
	 * @return PropelModelPager[Record]
	 */
	public function findPaginatedByFilterData(AbstractFilterData $data) {
		$q = $this->queryFilteredByFilterData($data);
		$this->applyOrderByFilterData($q, $data);
		return $q->paginate($data->pagenbr, $data->limit);
	}

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Add Wildcard
	 * @return string
	 */
	protected function wildcardify($keyword) {
		$keyword = $this->wire('sanitizer')->text($keyword);
		return static::WILDCARD_CHAR . str_replace(' ', static::WILDCARD_CHAR, $keyword) . static::WILDCARD_CHAR;
	}

	/**
	 * Return Key Value Array of Columns to their Table Map Equivalent
	 * @param  Query   $q     Query
	 * @param  Record  $class Record Class
	 * @return array
	 */
	protected function tableMapColumns(Query $q, Record $class) {
		$cols = [];

		foreach (static::COLUMNS_SEARCH as $column) {
			$cols[$column] = $q->tablecolumn($class::aliasproperty($column));
		}
		return $cols;
	}
}