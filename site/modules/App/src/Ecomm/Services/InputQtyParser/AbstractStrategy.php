<?php namespace App\Ecomm\Services\InputQtyParser;
// ProcessWire
use ProcessWire\WireInputData;

/**
 * AbstractStrategy
 * Strategy for Parsing Qty from Input
 * 
 * @property int $decimalPrecision  Decimal Precision for Return Qty
 */
abstract class AbstractStrategy {
	const TYPE = 'default';
	public $decimalPrecision = 2;

	/**
	 * Set Decimal Precision for Qty
	 * @param  int $precisiion
	 * @return void
	 */
	public function setDecimalPrecision($precision = 0) {
		$this->decimalPrecision = $precision;
	}

	/**
	 * Return Qty Parsed from Input Data
	 * @param  WireInputData $input
	 * @param  string        $inputKey Key / Property to read qty from
	 * @return float
	 */
	public function parse(WireInputData $input, $inputKey = 'qty') {
		return $input->float($inputKey, ['precision' => $this->decimalPrecision]);
	}
}
