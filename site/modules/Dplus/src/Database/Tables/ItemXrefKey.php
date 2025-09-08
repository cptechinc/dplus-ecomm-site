<?php namespace  Dplus\Database\Tables;
// Dplus Models
use ItemXrefKeyQuery as Query;
// use ItemXrefKey as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;


/**
 * Reads Records from ItemXrefKey table
 * 
 * @method Query query()
 * @static self  instance()
 */
class ItemXrefKey extends AbstractQueryWrapper {
	const MODEL              = 'ItemXrefKey';
	const MODEL_KEY          = 'itemid';
	const MODEL_TABLE        = 'item_xref_key';
	const DESCRIPTION        = 'Dplus Item X-Ref Key table';
	const COLUMNS_SEARCH = [
		'xitemid',
		'itemid',
		'description',
	];

	const SOURCES = [
		0 => 'item',
		1 => 'cxm',
		2 => 'mxrfe',
		3 => 'vxm',
		4 => 'i2ip',
		5 => 'i2ic',
		6 => 'upcx',
		7 => 'shortitem',
		8 => 'nonstock',
		9 => 'ilookup',
	];

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */

/* =============================================================
	Reads
============================================================= */
	
/* =============================================================
	Supplemental
============================================================= */

}