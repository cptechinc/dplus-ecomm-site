<?php namespace Dpay\Db\Tables;
// Dpay
use Dpay\Abstracts\AbstractFilterData;
use Dpay\Db\QuerySelect as Query;
use Dpay\Db\Tables\PaymentLinks\FilterData;
use Dpay\Db\Tables\PaymentLinks\Record;


/**
 * PaymentLinks
 * Handles Reading from PaymentLinks table
 * @property int $conbr
 */
class PaymentLinks extends AbstractDatabaseTable {
	const TABLE = 'app_paymentlinks';
	const MODEL_CLASS = '\\Dpay\\Db\\Tables\\PaymentLinks\\Record';

	/** @var static */
	protected static $instance;

	public function init() {
		$this->conbr = $this->wire('config')->companyNbr;
	}

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query Filtered By Filter Data
	 * @param  FilterData $data
	 * @return Query
	 */
	public function queryFilteredByFilterData(AbstractFilterData $data) {
		$q = $this->query();
		$q->select('*');
		$q->where('conbr=:conbr AND custid=:custid', [':conbr' => $this->conbr, ':custid' => $data->custid]);
		return $q;
	}

/* =============================================================
	Read Functions
============================================================= */
	/**
	 * Return if Record Exists
	 * @param  string $id
	 * @return bool
	 */
	public function exists($id) {
		$q = $this->query();
		$q->select('COUNT(*)');
		$q->where('conbr=:conbr AND id=:id', [':conbr' => $this->conbr, ':id' => $id]);
		return boolval($q->execute()->fetchColumn());
	}

	/**
	 * Return if Record Data already exists
	 * @param  array $record
	 * @return bool
	 */
	public function existsArray(array $record) {
		$q = $this->query();
		$q->select('COUNT(*)');
		$q->where('conbr=:conbr AND id=:id', [':conbr' => $this->conbr, ':id' => $record['id']]);
		return boolval($q->execute()->fetchColumn());
	}

	/**
	 * Return if Record with Order # exists
	 * @param  int $ordernbr
	 * @return bool
	 */
	public function existsByOrdernbr($ordernbr) {
		$q = $this->query();
		$q->select('COUNT(*)');
		$q->where('conbr=:conbr AND ordernbr=:ordernbr', [':conbr' => $this->conbr, ':ordernbr' => $ordernbr]);
		return boolval($q->execute()->fetchColumn());
	}

	/**
	 * Return Record
	 * @param  string $id Record ID
	 * @return Record
	 */
	public function record($id) {
		$q = $this->query();
		$q->select('*');
		$q->where('conbr=:conbr AND id=:id', [':conbr' => $this->conbr, ':id' => $id]);
		return $q->execute()->fetch(static::MODEL_CLASS);
	}

	/**
	 * Return newest Record for Order #
	 * @param  int $ordernbr
	 * @return Record
	 */
	public function recordByOrdernbr($ordernbr) {
		$q = $this->query();
		$q->select('*');
		$q->where('conbr=:conbr AND ordernbr=:ordernbr', [':conbr' => $this->conbr, ':ordernbr' => $ordernbr]);
		return $q->execute()->fetchObject(static::MODEL_CLASS);
	}

	/**
	 * Return list of records
	 * @param  FilterData $data
	 * @return array(Record)
	 */
	public function findPaged(AbstractFilterData $data) {
		$q = $this->queryFilteredByFilterData($data);
		$q->orderby("$data->sortby $data->sortdir");
		$q->limit($data->offset() . ",$data->limit");
		return $q->execute()->fetchAll(\PDO::FETCH_CLASS, static::MODEL_CLASS);
	}

	/**
	 * Count Records that match filter
	 * @param  FilterData $data
	 * @return int
	 */
	public function count(AbstractFilterData $data) {
		$q = $this->queryFilteredByFilterData($data);
		$q->select('COUNT(*)');
		return intval($q->execute()->fetchColumn());
	}
}