<?php namespace App\Ecomm\Database;
// Propel ORM Library
use Propel\Runtime\Collection\ObjectCollection;
// Dpluso Model
use StatesQuery as Query, States as Record;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;

/**
 * States
 * Reads Records from States table
 * 
 * @method Query query()
 * @static self  $instance
 */
class States extends AbstractQueryWrapper {
	const MODEL 			 = 'States';
	const MODEL_KEY 		 = 'sessionid';
	const MODEL_TABLE		 = 'states';
	const DESCRIPTION		 = 'Ecomm states table';

	protected static $instance;

/* =============================================================
	Query Functions
============================================================= */

/* =============================================================
	Reads
============================================================= */
	/**
	 * Return All Records
	 * @return ObjectCollection[Record]
	 */
	public function all() {
		return $this->query()->find();
	}
}