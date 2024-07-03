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
 * @property string $fromdate Order Date From
 * @property string $thrudate Order Date Through
 */
class FilterData extends AbstractFilterData {
	const DEFAULT_SORTBY  = 'orderdate';
	const DEFAULT_SORTDIR = 'DESC';

	public function __construct() {
		parent::__construct();
		$this->custid   = $this->session->ecuser->custid;
		$this->fromdate = '';
		$this->thrudate = '';
	}

	/**
	 * Set Filter Data from WireInput
	 * @param  WireInputData $input
	 * @return void
	 */
	public function setFromWireInputData(WireInputData $input) {
		$this->fromdate = $input->text('fromdate');
		$this->thrudate = $input->text('thrudate');
		$this->sortby   = $input->text('orderBy');
		$this->sortdir  = $input->text('sortDir');
	}
}