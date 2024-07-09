<?php namespace Dplus\Database\Tables;
// Propel ORM Library
	// use Propel\Runtime\Util\PropelModelPager;
// Dplus Models
use SalesOrderQuery as Query, SalesOrder as Record;
// Dplus
	// use Dplus\Abstracts\AbstractQueryWrapper;
	// use Dplus\Abstracts\AbstractFilterData;

/**
 * SalesOrder
 * Reads Records from SalesOrder table
 * 
 * @method Query query()
 * @static self  $instance
 */
class SalesOrder extends AbstractOrderTable {
	const MODEL              = 'SalesOrder';
	const MODEL_KEY          = 'ordernumber';
	const MODEL_TABLE        = 'so_header';
	const DESCRIPTION        = 'Dplus Sales Orders table';

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */

/* =============================================================
	Reads
============================================================= */

}