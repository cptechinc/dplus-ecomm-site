<?php namespace Dplus\Database\Tables\CodeTables;
// Propel ORM Library
	// use Propel\Runtime\ActiveQuery\ModelCriteria as AbstractQuery;
	// use Propel\Runtime\ActiveRecord\ActiveRecordInterface as AbstractRecord;
// Dplus Models
use UnitofMeasureSaleQuery as Query, UnitofMeasureSale as Record;
// Dplus
	// use Dplus\Abstracts\AbstractQueryWrapper;
use Dplus\Database\Tables\AbstractCodeTable;

/**
 * UnitOfMeasureSale
 * Handles Reading Records from Ship-Via Table
 * 
 * @method Query   query()
 * @method Record newRecord()
 * @static self  $instance
 */
class UnitOfMeasure extends AbstractCodeTable {
	const MODEL              = 'UnitofMeasureSale';
	const MODEL_KEY          = 'code';
	const MODEL_TABLE        = 'inv_uom_sale';
	const DESCRIPTION        = 'Dplus Unit Of Measure table';
	const CODETABLE_CODE     = 'umm';
	const PRICEBYWEIGHT_CATCHWEIGHT    = 'C';
	const PRICEBYWEIGHT_STANDARDWEIGHT = 'S';

	protected static $instance;
}