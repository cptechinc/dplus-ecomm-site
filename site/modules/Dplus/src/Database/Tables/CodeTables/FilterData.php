<?php namespace Dplus\Database\Tables\CodeTables;
// ProcessWire
use ProcessWire\WireInputData;
// Dplus
use Dplus\Abstracts\AbstractFilterData;

/**
 * FilterData
 * Container for Filter Data for CodeTables
 *
 * @property string $sortby   Field to Sort By
 * @property string $sortdir  Direction to Sort By
 * @property string $query     Search Query
 * @property string $useWildcardSearch           Use Wildcard Search?
 * @property string $useWildcardSearchUppercase  Use Wildcard Search In Uppercase?
 */
class FilterData extends AbstractFilterData {
	const DEFAULT_SORTBY    = 'code';
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
		$this->query    = $input->text('q');
		$this->useWildcardSearch = $input->bool('useWildcardSearch');
		$this->useWildcardSearchUppercase = $input->bool('useWildcardSearchUppercase');
	}
}