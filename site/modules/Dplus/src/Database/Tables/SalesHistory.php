<?php namespace Dplus\Database\Tables;
// Propel ORM Library
	// use Propel\Runtime\Util\PropelModelPager;
use Propel\Runtime\ActiveQuery\ModelCriteria as AbstractQuery;
// Dplus Models
use SalesHistoryQuery as Query, SalesHistory as Record;
// Dplus
	// use Dplus\Abstracts\AbstractQueryWrapper;
	use Dplus\Abstracts\AbstractFilterData;

/**
 * SalesHistory
 * Reads Records from SalesHistory table
 * 
 * @method Query query()
 * @static self  $instance
 */
class SalesHistory extends AbstractOrderTable {
	const MODEL              = 'SalesHistory';
	const MODEL_KEY          = 'ordernumber';
	const MODEL_TABLE        = 'so_head_hist';
	const DESCRIPTION        = 'Dplus Sales History table';
	const DATEFIELD_OPTIONS   = [
		'orderdate'   => 'Order Date',
		'invoicedate' => 'Invoice Date',
	];

	protected static $instance;

	/**
	 * Return List of Options for Date Field
	 * @return bool
	 */
	public function datefieldOptions() {
		return self::DATEFIELD_OPTIONS;
	}

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query Filtered By Filter Data
	 * @param  SalesOrder\FilterData $data
	 * @return Query
	 */
	public function queryFilteredByFilterData(AbstractFilterData $data) {
		$q = parent::queryFilteredByFilterData($data);
		if ($data->custpo) {
			$q->filterByCustpo($data->custpo);
		}
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
		if (Record::aliasproperty_exists($data->datefield) === false) {
			return true;
		}
		$colOrderdate = Record::aliasproperty($data->datefield);
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

}