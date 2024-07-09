<?php namespace  Dplus\Database\Tables;
// Propel ORM Library
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
	protected function applyFilterDataOrderdateFilter(Query $q, SalesOrder\FilterData $data) {
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
	
/* =============================================================
	Reads
============================================================= */
	/**
	 * Return Results Paginated
	 * @return PropelModelPager[Record]
	 */
	public function findPaginatedByFilterData(SalesOrder\FilterData $data) {
		$q = $this->queryFilteredByFilterData($data);
		$this->applyOrderByFilterData($q, $data);
		return $q->paginate($data->pagenbr, $data->limit);
	}
}