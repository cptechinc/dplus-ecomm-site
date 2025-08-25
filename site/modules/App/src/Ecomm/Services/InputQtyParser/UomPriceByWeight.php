<?php namespace App\Ecomm\Services\InputQtyParser;
// Dplus Models
use ItemMasterItem as ItemRecord;
// ProcessWire
use ProcessWire\WireInputData;
// Dplus
use Dplus\Database\Tables\CodeTables\UnitOfMeasure as UomTable;
use Dplus\Database\Tables\Item as ItemTable;

/**
 * UomPriceByWeight
 * Strategy for Parsing Qty from Input using Item's UoM Price By Weight
 * 
 * @property int $decimalPrecision  Decimal Precision for Return Qty
 */
class UomPriceByWeight extends AbstractStrategy {
	const TYPE = 'uom-price-by-weight';
	
	/**
	 * Return Qty Parsed from Input Data
	 * @param  WireInputData $input
	 * @param  string        $inputKey Key / Property to read qty from
	 * @return float
	 */
	public function parse(WireInputData $input, $inputKey = 'qty') {
		/** @var ItemRecord */
		$item = ItemTable::instance()->item($input->string('itemID'));

		if (empty($item)) {
			return $input->float('qty', ['precision' => $this->decimalPrecision]);
		}

		// Parse Qty normally if not standard weight
		if (in_array($item->unitofmsale->pricebyweight,[UomTable::PRICEBYWEIGHT_STANDARDWEIGHT, UomTable::PRICEBYWEIGHT_CATCHWEIGHT]) === false) {
			return $input->float('qty', ['precision' => $this->decimalPrecision]);
		}


		if ($input->offsetExists('weight') && $input->float('weight') > 0) {
			return $input->float('weight', ['precision' => $this->decimalPrecision]);
		}

		$input->inputQty = $input->int('cases');
		$input->qty = $input->inputQty * $item->weight;
		return $input->float('qty', ['precision' => $this->decimalPrecision]);
	}
}
