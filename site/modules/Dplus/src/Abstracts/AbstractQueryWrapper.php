<?php namespace Dplus\Abstracts;
// Propel Classes
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
}