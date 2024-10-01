<?php namespace  Dplus\Database\Tables;
// Propel ORM Library
	// use Propel\Runtime\ActiveQuery\ModelCriteria as AbstractQuery;
use Propel\Runtime\Util\PropelModelPager;
// Dplus Models
use ArInvoiceQuery as Query, ArInvoice as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;
use Dplus\Abstracts\AbstractFilterData;

/**
 * ArInvoice
 * Reads Records from ArInvoice table
 * 
 * @method Query query()
 * @static self  $instance
 */
class ArInvoice extends AbstractQueryWrapper {
	const MODEL              = 'ArInvoice';
	const MODEL_KEY          = 'ordernumber';
	const MODEL_TABLE        = 'ar_inv';
	const DESCRIPTION        = 'Dplus AR Invoices table';
	const ORDN_NBROF_DIGITS  = 10;
	const TYPE_INVOICE = 'I';

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query Filtered By Filter Data
	 * @param  ArInvoice\FilterData $data
	 * @return Query
	 */
	public function queryFilteredByFilterData(AbstractFilterData $data) {
		$q = $this->query();
		$q->filterByCustid($data->custid);
		$q->filterByType($data->type);
		return $q;
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
	 * @param  AbstractFilterData $data
	 * @return PropelModelPager[Record]
	 */
	public function findPaginatedByFilterData(AbstractFilterData $data) {
		$q = $this->queryFilteredByFilterData($data);
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
	public function invoice($ordn) {
		return $this->queryOrdn($ordn)->findOne();
	}

	/**
	 * Return Invoice Payments Total for Invoice
	 * @param  string $invnbr
	 * @return float
	 */
	public function getInvoicePaymentsTotal($invnbr) {
		$col = Record::aliasproperty('total');
		$q = $this->query();
		$q->filterByType(self::TYPE_INVOICE, '!=');
		$q->filterByInvoicenumber($invnbr);
		$q->withColumn("SUM($col)", 'total');
		$q->select('total');
		return floatval($q->findOne());
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