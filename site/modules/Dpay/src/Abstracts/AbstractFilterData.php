<?php namespace Dpay\Abstracts;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;

/**
 * AbstractFilterData
 * Container for Filtering Data
 * 
 * @property int    $pagenbr   Page Number
 * @property int    $limit     Number of Results to return
 * @property string $sortby    Field to Sort by
 * @property string $sortdir   Sort Direction
 * @property string $query     Search Query
 * @property string $useWildcardSearch  Use Wildcard Search?
 * @property string $useWildcardSearchUppercase  Use Wildcard Search In Uppercase?
 */
abstract class AbstractFilterData extends WireData {
	const FIELDS_FILTERABLE = [];
	const DEFAULT_SORTBY  = '';
	const DEFAULT_SORTDIR = '';
	
	public function __construct() {
		$this->pagenbr = 1;
		$this->limit   = 0;
		$this->sortby  = '';
		$this->sortdir = '';
		$this->query   = '';
		$this->useWildcardSearch = false;
		$this->useWildcardSearchUppercase = false;
	}

	/**
	 * Return Offset for Query Results
	 * @return int
	 */
	public function offset() {
		if ($this->pagenbr == 1) {
			return 0;
		}
		return $this->pagenbr * $this->limit - $this->limit;
	}

	/**
	 * Set Filter Data from WireInput
	 * @param  WireInputData $input
	 * @return void
	 */
	abstract public function setFromWireInputData(WireInputData $input);
	
	/**
	 * Return instance
	 * @param  WireInputData $input
	 * @return static
	 */
	public static function fromWireInputData(WireInputData $input) {
		$filter = new static();
		$filter->setFromWireInputData($input);
		return $filter;
	}

	/**
	 * Return instance
	 * @param  WireInputData $input
	 * @return static
	 */
	public static function fromWireData(WireData $data) {
		$input = new WireInputData();
		$input->setArray($data->data);
		return static::fromWireInputData($input);
	}
}