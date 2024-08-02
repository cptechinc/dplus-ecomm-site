<?php namespace  Dplus\Database\Tables;
// Propel ORM Library
use Propel\Runtime\ActiveQuery\ModelCriteria as AbstractQuery;
use Propel\Runtime\Util\PropelModelPager;
// Dplus Models
use SalesOrderQuery as Query, SalesOrder as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;
use Dplus\Abstracts\AbstractFilterData;

/**
 * AbstractOrderTable
 * Template for reading Records from Order tables
 * 
 * @method Query query()
 * @static self  $instance
 */
abstract class AbstractOrderTable extends AbstractQueryWrapper {
	const MODEL              = 'SalesOrder';
	const MODEL_KEY          = 'ordernumber';
	const MODEL_TABLE        = 'so_header';
	const DESCRIPTION        = 'Dplus Sales Orders table';
	const ORDN_NBROF_DIGITS  = 10;

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query Filtered By Filter Data
	 * @param  SalesOrder\FilterData $data
	 * @return Query
	 */
	public function queryFilteredByFilterData(AbstractFilterData $data) {
		$q = $this->query();
		$q->filterByCustid($data->custid);
		$this->applyFilterDataOrderdateFilter($q, $data);
		return $q;
	}

	/**
	 * Add Filter for Orderdate
	 * @param  Query                 $q
	 * @param  SalesOrder\FilterData $data
	 * @return true
	 */
	protected function applyFilterDataOrderdateFilter(AbstractQuery $q, SalesOrder\FilterData $data) {
		if (empty($data->datefrom) && empty($data->datethru)) {
			return true;
		}
		$colOrderdate = Record::aliasproperty('orderdate');
		$datefrom = date('Ymd', strtotime($data->datefrom));
		$datethru = date('Ymd', strtotime($data->datethru));
		// $params = [':datefrom' => $datefrom, ':datethru' => $datethru];
		// $q->where("($colOrderdate BETWEEN :datefrom AND :datethru)", $params);
		$q->condition('from', static::MODEL . ".$colOrderdate >= ?", $datefrom);
		$q->condition('thru', static::MODEL . ".$colOrderdate <= ?", $datethru);
		$q->where(array('from', 'thru'), 'AND');
		return true;
	}

	/**
	 * Return Query Filtered By Order Number
	 * @param  int   $ordn
	 * @return Query
	 */
	public function queryOrdn($ordn) {
		return $this->query()->filterByOrdernumber($ordn);
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
	 * @param  int $ordn
	 * @return bool
	 */
	public function exists($ordn) {
		return boolval($this->queryOrdn($ordn)->count());
	}

	/**
	 * Return if Order is for Customer
	 * @param  int $ordn
	 * @param  string $custID
	 * @return bool
	 */
	public function isForCustid($ordn, $custID) {
		return boolval($this->queryOrdn($ordn)->filterByCustid($custID)->count());
	}

	/**
	 * Return Order
	 * @param  int $ordn
	 * @return Record
	 */
	public function order($ordn) {
		return $this->queryOrdn($ordn)->findOne();
	}

/* =============================================================
	Supplementals
============================================================= */
	/**
	 * Adds Leading Zeroes to Sales Order Number
	 * @param  string $ordn Sales Order Number ex.    4290100
	 * @return string       Sales Order Number ex. 0004290100
	 */
	public static function padOrdernumber($ordn) {
		return str_pad($ordn , self::ORDN_NBROF_DIGITS , "0", STR_PAD_LEFT);
	}
}