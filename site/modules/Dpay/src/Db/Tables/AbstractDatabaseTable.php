<?php namespace Dpay\Db\Tables;
// Base PHP
	// use Exception;
// ProcessWire
use ProcessWire\WireData;
// Pauldro ProcessWire
use Pauldro\ProcessWire\DatabaseTables\Record;
// Dpay
use Dpay\Db\QuerySelect;

/**
 * AbstractDatabaseTable
 * Handles Reading from external Dpay Database Table
 */
abstract class AbstractDatabaseTable extends WireData {
	const TABLE = '';
	const FORMAT_DATETIME = 'Y-m-d H:i:s';
	const COLUMNS = [];
	const PRIMARYKEY = [];
	const RECORDKEY  = [];
	const MODEL_CLASS = '\\Pauldro\\ProcessWire\\DatabaseTables\\Record';
	const KEY_GLUE    = '|';

	/** @var static */
	protected static $instance;

	/** @return static */
	public static function instance() {
		if (empty(static::$instance)) {
			$instance = new static();
			static::$instance = $instance;
		}
		return static::$instance;
	}

	public function init() {
		
	}

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query
	 * @return QuerySelect
	 */	
	protected function query() {
		$q = new QuerySelect();
		$q->from(static::TABLE);
		return $q;
	}

	/**
	 * Return Query Filtered by Primary Key
	 * @param  array $key
	 * @return QuerySelect
	 */
	protected function queryKey(array $key) {
		$data = $this->new();
		$data->setArray($key);
		
		$where  = $this->getParamsForQuery(static::RECORDKEY, ' AND ');
		$params = $this->getParamKeyValues($data, static::RECORDKEY);

		$q = $this->query();
		$q->where($where, $params);
		return $q;
	}

	/**
	 * Return Query Filtered by Primary Key
	 * @param  array $key
	 * @return QuerySelect
	 */
	protected function queryPrimaryKey(array $key) {
		$data = $this->new();
		$data->setData($key);
		
		$where  = $this->getParamsForQuery(static::PRIMARYKEY, ' AND ');
		$params = $this->getParamKeyValues($data, static::PRIMARYKEY);

		$q = $this->query();
		$q->where($where, $params);
		return $q;
	}

	/**
	 * Return instance of Record
	 * @return Record
	 */
	public function new() {
		$class = static::MODEL_CLASS;
		return new $class();
	}

/* =============================================================
	Read Functions
============================================================= */
	/**
	 * Return the total number of records
	 * @return int
	 */
	public function countAll() {
		$q = $this->query();
		$q->select('COUNT(*)');
		return intval($q->execute()->fetchColumn());
	}
	
	/**
	 * Return if Record Exists
	 * @param  array $key
	 * @return bool
	 */
	public function existsByPrimaryKey(array $key) {
		$q = $this->queryPrimaryKey($key);
		$q->select('COUNT(*)');
		return boolval($q->execute()->fetchColumn());
	}

	/**
	 * Return if Record Exists
	 * @param  array $key
	 * @return bool
	 */
	public function existsByKey(array $key) {
		$q = $this->queryKey($key);
		$q->select('COUNT(*)');
		return boolval($q->execute()->fetchColumn());
	}

	/**
	 * Return All Records
	 * @return array[Model]
	 */
	public function findAll() {
		$q = $this->query();
		$q->select('*');
		return $q->execute()->fetchObject(self::MODEL_CLASS);
	}

	/**
	 * Return if Record Exists
	 * @param  string $key
	 * @return bool
	 */
	abstract public function exists($key);

	/**
	 * Return if Record Data already exists
	 * @param  array $record
	 * @return bool
	 */
	abstract public function existsArray(array $record);

/* =============================================================
	Create, Update, Delete Functions
============================================================= */
	

/* =============================================================
	Param, Key Functions
============================================================= */
	/**
	 * Return Parameters for Set
	 * @param  array $cols
	 * @return string		column=:column
	 */
	protected function getParamsForQuery($cols, $glue = ',') {
		$data = [];
		foreach ($cols as $col) {
			$data[] = "$col=:$col";
		}
		return implode($glue, $data);
	}

	/**
	 * Return Parameters Key Arrays
	 * @param  Record    $data
	 * @return string		  :col1,:col2
	 */
	protected function getParamKeysArray(Record $data) {
		return  array_keys($data->data);
	}

	/**
	 * Return Parameters Key String
	 * @param  Record    $data
	 * @return string		  :col1,:col2
	 */
	protected function getParamKeysString(Record $data) {
		return ':' . implode(',:', array_keys($data->data));
	}

	/**
	 * Return Parameters Keyed by Param Key
	 * @param  Record $data
	 * @param  array  $keys	
	 * @return array		   [':key' => $value]
	 */
	protected function getParamKeyValues(Record $data, $keys = []) {
		$params = [];

		foreach ($data->data as $key => $value) {
			if (in_array($key, $keys) || empty($keys)) {
				$params[':' . $key] = $value;
			}
		}
		return $params;
	}

	/**
	 * Return Parameters Keyed by Param Key
	 * @param  array $data
	 * @param  array $keys	
	 * @return array		   [':key' => $value]
	 */
	protected function getParamKeyValuesArray(array $data, $keys = []) {
		$params = [];

		foreach ($data as $key => $value) {
			if (in_array($key, $keys) || empty($keys)) {
				$params[':' . $key] = $value;
			}
		}
		return $params;
	}

	/**
	 * Return if Record has the needed column as keys
	 * @param  array $record
	 * @return bool
	 */
	protected function validateArrayKeys(array $record) {
		foreach (array_keys(static::COLUMNS) as $col) {
			if (array_key_exists($col, $record) === false) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Return if array has Primary Keys
	 * @param  array $r
	 * @return bool
	 */
	protected function arrayHasRecordKeys(array $r) {
		foreach (static::RECORDKEY as $col) {
			if (array_key_exists($col, $r) === false) {
				return false;
			}
		}
		return true;
	}
}