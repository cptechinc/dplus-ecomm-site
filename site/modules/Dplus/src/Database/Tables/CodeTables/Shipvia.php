<?php namespace Dplus\Database\Tables\CodeTables;
// Propel ORM Library
	// use Propel\Runtime\ActiveQuery\ModelCriteria as AbstractQuery;
	// use Propel\Runtime\ActiveRecord\ActiveRecordInterface as AbstractRecord;
// Dplus Models
use ShipviaQuery as Query, Shipvia as Record;
// Dplus
	// use Dplus\Abstracts\AbstractQueryWrapper;
use Dplus\Database\Tables\AbstractCodeTable;

/**
 * Shipvia
 * Handles Reading Records from Ship-Via Table
 * 
 * @method Query   query()
 * @method Record newRecord()
 * @static self  $instance
 */
class Shipvia extends AbstractCodeTable {
	const MODEL              = 'Shipvia';
	const MODEL_KEY          = 'code';
	const MODEL_TABLE        = 'ar_cust_svia';
	const DESCRIPTION        = 'Dplus Ship-Via table';
	const CODETABLE_CODE     = 'csv';

	protected static $instance;
}