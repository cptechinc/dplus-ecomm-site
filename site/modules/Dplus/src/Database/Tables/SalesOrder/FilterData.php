<?php namespace Dplus\Database\Tables\SalesOrder;
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
 * @property string $custpo   Customer PO #
 */
class FilterData extends AbstractFilterData {
	const DEFAULT_SORTBY  = 'orderdate';
	const DEFAULT_SORTDIR = 'DESC';

	public function __construct() {
		parent::__construct();
		$this->custid   = $this->session->ecuser->custid;
		$this->datefrom = '';
		$this->datethru = '';
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
	}
}