<?php namespace Dpay\Db\Tables;
// Dpay
use Dpay\Db\Tables\Data\PaymentLink as Record;

/**
 * PaymentLinks
 * Handles Reading from PaymentLinks table
 */
class PaymentLinks extends AbstractDatabaseTable {
	const TABLE = 'app_paymentlinks';
	const MODEL_CLASS = '\\Dpay\\Db\\Tables\\Data\\PaymentLink';

	/** @var static */
	protected static $instance;

/* =============================================================
	Read Functions
============================================================= */
	/**
	 * Return if Record Exists
	 * @param  string $key
	 * @return bool
	 */
	public function exists($key) {
		$q = $this->query();
		$q->select('COUNT(*)');
		$q->where('id=:id', [':id' => $key]);
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
		$q->where('id=:id', [':id' => $record['id']]);
		return boolval($q->execute()->fetchColumn());
	}

	/**
	 * Return if Record with Order # exists
	 * @param  int $ordn
	 * @return bool
	 */
	public function existsByOrdn($ordn) {
		$q = $this->query();
		$q->select('COUNT(*)');
		$q->where('ordn=:ordn', [':ordn' => $ordn]);
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
		$q->where('id=:id', [':id' => $id]);
		return $q->execute()->fetch(static::MODEL_CLASS);
	}

	/**
	 * Return newest Record for Order #
	 * @param  int $ordn
	 * @return Record
	 */
	public function recordByOrdn($ordn) {
		$q = $this->query();
		$q->select('*');
		$q->where('ordn=:ordn', [':ordn' => $ordn]);
		return $q->execute()->fetchObject(static::MODEL_CLASS);
	}
}