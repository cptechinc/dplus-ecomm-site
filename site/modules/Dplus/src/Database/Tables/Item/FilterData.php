<?php namespace Dplus\Database\Tables\Item;
// ProcessWire
use ProcessWire\WireInputData;
// Dplus
use Dplus\Abstracts\AbstractFilterData;

/**
 * FilterData
 * Container for Filter Data for Item Table
 *
 * @property string $sortby   Field to Sort By
 * @property string $sortdir  Direction to Sort By
 */
class FilterData extends AbstractFilterData {
	const DEFAULT_SORTBY    = 'itemid';
	const DEFAULT_SORTDIR   = 'ASC';

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Set Filter Data from WireInput
	 * @param  WireInputData $input
	 * @return void
	 */
	public function setFromWireInputData(WireInputData $input) {
		$this->sortby   = $input->text('orderBy');
		$this->sortdir  = $input->text('sortDir');
	}
}