<?php namespace Dpay\Db\Tables\PaymentLinks;
// ProcessWire
use ProcessWire\WireInputData;
// Dpay
use Dpay\Abstracts\AbstractFilterData;

/**
 * FilterData
 * Container for Filter Data for Sales Order Table
 *
 * @property string $sortby   Field to Sort By
 * @property string $sortdir  Direction to Sort By
 * @property string $custid   Customer ID
 */
class FilterData extends AbstractFilterData {
	const DEFAULT_SORTBY    = 'timestamp';
	const DEFAULT_SORTDIR   = 'DESC';
	

	public function __construct() {
		parent::__construct();
		$this->custid   = $this->session->ecuser->custid;
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
	}
}