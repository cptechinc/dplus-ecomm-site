<?php namespace Dplus\Database\Tables\ArInvoice;
// ProcessWire
use ProcessWire\WireInputData;
// Dplus
use Dplus\Abstracts\AbstractFilterData;

/**
 * FilterData
 * Container for Filter Data for Sales Order Table
 *
 * @property string $sortby   Field to Sort By
 * @property string $sortdir  Direction to Sort By
 * @property string $custID   Customer ID
 * @property string $datefrom Order Date From
 * @property string $datethru Order Date Through
 * @property string $type     Invoice Type
 * @property string $datefield  Date Field (invoicedate, orderdate)
 */
class FilterData extends AbstractFilterData {
	const DEFAULT_SORTBY    = 'orderdate';
	const DEFAULT_SORTDIR   = 'DESC';
	const DEFAULT_DATEFIELD = 'orderdate';
	const DATEFIELD_OPTIONS = [
		'orderdate'   => 'Order Date',
		'invoicedate' => 'Invoice Date',
	];
	const DEFAULT_TYPE = 'I';

	public function __construct() {
		parent::__construct();
		$this->custid   = $this->session->ecuser->custid;
		$this->datefrom = '';
		$this->datethru = '';
		$this->datefield = self::DEFAULT_DATEFIELD;
		$this->type      = self::DEFAULT_TYPE;
	}

	/**
	 * Set Filter Data from WireInput
	 * @param  WireInputData $input
	 * @return void
	 */
	public function setFromWireInputData(WireInputData $input) {
		$this->datefrom = $input->text('datefrom');
		$this->datethru = $input->text('datethru');
		$this->sortby   = $input->text('orderBy');
		$this->sortdir  = $input->text('sortDir');
		$this->custpo   = $input->string('custpo');
		$this->datefield = $input->option('datefield', array_keys(self::DATEFIELD_OPTIONS));

		if (empty($this->datefield)) {
			$this->datefield = self::DEFAULT_DATEFIELD; 
		}
	}
}