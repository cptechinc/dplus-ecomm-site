<?php namespace App\Ecomm\Services;
// Propel ORM Library
use Propel\Runtime\Util\PropelModelPager;
	// use Propel\Runtime\Collection\ObjectCollection;
// Dplus Model
	// use ArInvoice as Record;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Abstracts\AbstractFilterData;
use Dplus\Database\Tables\Configs;
use Dplus\Database\Tables\ArInvoice as InvoiceTable;
// App
use App\Ecomm\Abstracts\Services\AbstractService;


/**
 * ArInvoices
 * Provides Cart Services
 * 
 * @property InvoiceTable $table
 */
class ArInvoices extends AbstractService {
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->periods = [];
		$this->init();
		$this->table = InvoiceTable::instance();
	}

	public function init() {
		$this->initPeriods();
	}

	private function initPeriods() {
		$config = Configs\Ar::config();

		$period1 = new WireData();
		$period1->start = 1;
		$period1->days  = intval($config->overduestmtdays1);
		$period1->title = "$period1->start - $period1->days Days";

		$period2 = new WireData();
		$period2->start = $period1->days;
		$period2->days  = intval($config->overduestmtdays2);
		$period2->title = $period2->start + 1 . " - $period2->days Days";

		$period3 = new WireData();
		$period3->start = $period2->days;
		$period3->days  = 0;
		$period3->title = "Over $period2->days";
		$this->periods = [$period1, $period2, $period3];
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
		$invoices = $this->findArInvoices($data);
		
	}

	/**
	 * Return AR Invoices
	 * @param  AbstractFilterData $data
	 * @return PropelModelPager
	 */
	private function findArInvoices(AbstractFilterData $data) {
		InvoiceTable::instance()->findPaginatedByFilterData($data);
	}
	

/* =============================================================
	CRUD Processing
============================================================= */
	

/* =============================================================
	Dplus Requests
============================================================= */
	
}