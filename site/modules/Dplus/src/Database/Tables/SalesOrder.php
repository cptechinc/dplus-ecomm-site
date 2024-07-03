<?php namespace  Dplus\Database\Tables;
// Propel ORM Library
use Propel\Runtime\Util\PropelModelPager;
// Dplus Models
use SalesOrderQuery as Query, SalesOrder as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;
use Dplus\Abstracts\AbstractFilterData;

/**
 * SalesOrder
 * Reads Records from SalesOrder table
 * 
 * @method Query query()
 * @static self  $instance
 */
class SalesOrder extends AbstractQueryWrapper {
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
	private function applyFilterDataOrderdateFilter(Query $q, SalesOrder\FilterData $data) {
		if (empty($data->fromdate) && empty($data->thrudate)) {
			return true;
		}
		$colOrderdate = Record::aliasproperty('orderdate');
		$fromdate = date('Ymd', strtotime($data->fromdate));
		$thrudate = date('Ymd', strtotime($data->thrudate));
		$params = [':fromdate' => $fromdate, ':thrudate' => $thrudate];
		$q->where("($colOrderdate BETWEEN :fromdate AND :thrudate)", $params);
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