<?php namespace Dplus\Abstracts;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;

/**
 * AbstractFilterData
 * Container for Filtering Data
 * 
 * @property int    $pagenbr
 * @property int    $limit
 * @property string $sortby
 * @property string $sortdir
 */
abstract class AbstractFilterData extends WireData {
	const FIELDS_FILTERABLE = [];
	
	public function __construct() {
		$this->pagenbr = 1;
		$this->limit   = 0;
		$this->sortby  = '';
		$this->sortdir = '';
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