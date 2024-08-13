<?php namespace Dpay\Db;
// ProcessWire
use ProcessWire\WireDatabasePDO;
// Pauldro ProcessWire
use Pauldro\ProcessWire\DatabaseQuery\DatabaseQuerySelect;

/**
 * QuerySelect
 * Wrapper for SELECT queries for a Dpay database table
 */
class QuerySelect extends DatabaseQuerySelect {
	/**
	 * Return Database
	 * @return WireDatabasePDO
	 */
	public function database() {
		return Database::pwdb();
	}
}