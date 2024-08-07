<?php namespace  Dplus\Database\Tables\CodeTables;
// Propel ORM Library
	// use Propel\Runtime\ActiveQuery\ModelCriteria as AbstractQuery;
	// use Propel\Runtime\ActiveRecord\ActiveRecordInterface as AbstractRecord;
// Dplus Models
use InvGroupCodeQuery as Query, InvGroupCode as Record;
// Dplus
	// use Dplus\Abstracts\AbstractQueryWrapper;
use Dplus\Database\Tables\AbstractCodeTable;

/**
 * ItemGroup
 * Handles Reading Records from Inventory Group Table
 * 
 * @method Query   query()
 * @method Record newRecord()
 * @static self  $instance
 */
class ItemGroup extends AbstractCodeTable {
	const MODEL              = 'InvGroupCode';
	const MODEL_KEY          = 'code';
	const MODEL_TABLE        = 'inv_grup_code';
	const DESCRIPTION        = 'Dplus Inventory Group Code table';
	const CODETABLE_CODE     = 'igc';

	protected static $instance;
}