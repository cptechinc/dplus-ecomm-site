<?php namespace Dplus\Docm;
// Dplus Models
use Document;
use DocumentQuery as Query;
// ProcessWire
use ProcessWire\WireData;

/**
 * Base QueryWrapper
 */
class StaticDocumentQueryWrapper {
	protected static $columns;

	/**
	 * Return Query
	 * @return Query
	 */
	public static function query() {
		return Query::create();
	}

	/**
	 * Return Query
	 * @return Query
	 */
	protected static function _query() {
		return Query::create();
	}

/* =============================================================
	Supplemental Functions
============================================================= */
	/**
	 * Return Columns
	 * @return WireData
	 */
	public static function getColumns() {
		if (empty(self::$columns) === false) {
			return self::$columns; 
		}
		$columns = new WireData();
		$columns->tag = Document::aliasproperty('tag');
		$columns->reference1 = Document::aliasproperty('reference1');
		$columns->reference2 = Document::aliasproperty('reference2');
		$columns->folder     = Document::aliasproperty('folder');
		self::$columns = $columns;
		return self::$columns;
	}
}