<?php namespace  Dplus\Database\Tables;
// Propel ORM Library
	// use Propel\Runtime\ActiveQuery\ModelCriteria as AbstractQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as AbstractRecord;
use Propel\Runtime\Collection\ObjectCollection;
// Dplus Models
use ShipviaQuery as Query, Shipvia as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;

/**
 * AbstractCodeTable
 * Template for reading Records from Code tables
 * 
 * @method Query   query()
 * @method Record newRecord()
 * @static self  $instance
 */
abstract class AbstractCodeTable extends AbstractQueryWrapper {
	const MODEL              = 'Shipvia';
	const MODEL_KEY          = 'code';
	const MODEL_TABLE        = 'ar_cust_svia';
	const DESCRIPTION        = 'Dplus Ship-Via table';
	const CODETABLE_CODE     = 'csv';

	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		$this->cacher = CodeTables\Cacher::instance();
	}

/* =============================================================
	Reads
============================================================= */
	/**
	 * Return if Code Exists
	 * @param  string $code
	 * @return bool
	 */
	public function exists($code) {
		if ($this->existsInCache($code)) {
			return true;
		}
		return $this->existsInDb($code);
	}

	/**
	 * Return Code
	 * @param  string $code
	 * @return Record|false
	 */
	public function fetch($code) {
		$r = $this->fetchFromCache($code);

		if (empty($r) === false) {
			return $r;
		}
		$r = $this->fetchFromDb($code);
		if (empty($r) === false) {
			return $r;
		}
		$this->updateCache($r);
		return $r;
	}

	/**
	 * Return Code's Description
	 * @param  string $code
	 * @return string
	 */
	public function description($code) {
		$r = $this->fetch($code);
		if (empty($r)) {
			return '';
		}
		return $r->description;
	}

	/**
	 * Return All Codes
	 * NOTE: skips cache
	 * @return ObjectCollection
	 */
	public function all() {
		return $this->query()->find();
	}


	/**
	 * Return the Number of Records
	 * @return int
	 */
	public function countAll() {
		return $this->query()->count();
	}

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query Filtered By Code
	 * @param  string $code
	 * @return Query
	 */
	public function queryCode($code) {
		return $this->query()->filterByCode($code);
	}
	
/* =============================================================
	Reads
============================================================= */
	/**
	 * Return if Code Exists
	 * @param  string $code
	 * @return bool
	 */
	public function existsInDb($code) {
		return boolval($this->queryCode($code)->count());
	}

	/**
	 * Return Code
	 * @param  string $code
	 * @return Record
	 */
	public function fetchFromDb($code) {
		return $this->queryCode($code)->findOne();
	}

	/**
	 * Return the Number of Records
	 * @return int
	 */
	public function countInDb() {
		return $this->query()->count();
	}

/* =============================================================
	Cache
============================================================= */
	/**
	 * Return if Code exists in Cache
	 * @param  string $code
	 * @return bool
	 */
	public function existsInCache($code) {
		return $this->cacher->exists(static::CODETABLE_CODE, $code);
	}

	/**
	 * Return Record from Cache
	 * @param  string $code
	 * @return AbstractRecord|false
	 */
	public function fetchFromCache($code) {
		$data = $this->cacher->fetch(static::CODETABLE_CODE, $code);

		if (empty($data)) {
			return false;
		}
		$r = $this->newRecord();
		$r->fromArray($data);
		return $r;
	}

	/**
	 * Save Code in cache
	 * @param  Record $r
	 * @return bool
	 */
	protected function updateCache(AbstractRecord $r) {
		if (empty($r)) {
			return false;
		}
		return $this->cacher->save(static::CODETABLE_CODE, $r->code, $r->toArray());
	}
}