<?php namespace Dplus\Database\Tables;
// Propel ORM Library
	// use Propel\Runtime\ActiveQuery\ModelCriteria as AbstractQuery;
	// use Propel\Runtime\Util\PropelModelPager;
// Dplus Models
use CustomerQuery as Query, Customer as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;
use Dplus\Abstracts\AbstractFilterData;

/**
 * Customer
 * Reads Records from Customer table
 * 
 * @method Query query()
 * @static self  $instance
 */
class Customer extends AbstractQueryWrapper {
	const MODEL              = 'Customer';
	const MODEL_KEY          = 'custid';
	const MODEL_TABLE        = 'ar_cust_mast';
	const DESCRIPTION        = 'Dplus Customers table';

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */

	/**
	 * Return Query Filtered By Customer ID
	 * @param  int   $custid
	 * @return Query
	 */
	public function queryCustid($custid) {
		return $this->query()->filterByCustid($custid);
	}
	
/* =============================================================
	Reads
============================================================= */
	/**
	 * Return Results Paginated
	 * @return PropelModelPager[Record]
	 */
	public function findPaginatedByFilterData(AbstractFilterData $data) {
		$q = $this->queryFilteredByFilterData($data);
		$this->applyOrderByFilterData($q, $data);
		return $q->paginate($data->pagenbr, $data->limit);
	}

	/**
	 * Return if Order Exists
	 * @param  int $custid
	 * @return bool
	 */
	public function exists($custid) {
		return boolval($this->queryCustid($custid)->count());
	}

	/**
	 * Return Customer
	 * @param  string $custid
	 * @return Record
	 */
	public function findOne($custid) {
		return $this->queryCustid($custid)->findOne();
	}

}