<?php namespace Dplus\Docm\Finders;
// Propel ORM Library
	// use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
// Dplus Record
use DocumentQuery as Query, Document as Record;
// Dplus
use Dplus\Database\Tables\Arstmt as SoTable;
// Dplus
use Dplus\Docm\StaticDocumentQueryWrapper;
use Dplus\Docm\QueryDecorators;

/**
 * Arstmt
 * Decorator for DocumentQuery to find Documents in Database related to Customer ID
 */
class ArStmt extends StaticDocumentQueryWrapper {
/* =============================================================
	Read Functions
============================================================= */
	/**
	 * Return Documents related to Customer ID
	 * @param  string $custID  Customer ID
	 * @return ObjectCollection|Record[]
	 */
	public static function find($custID) {
		$q = self::query();
		self::filterCustid($q, $custID);
		return $q->find();
	}

	/**
	 * Return the number of Documents related to Customer ID
	 * @param  string $custID  Customer ID
	 * @return int
	 */
	public static function count($custID) {
		$q = self::query();
		self::filterCustid($q, $custID);
		return $q->count();
	}

/* =============================================================
	Query Decorator Functions
============================================================= */
	/**
	 * Adds Filter Conditions to the Documents Query
	 * to find Documents associated with a Custid Order
	 * @param  Query $q     Query
	 * @param  string       $custID  Customer ID
	 * @return Query
	 */
	public static function filterCustid(Query $q, $custID) {
		$conditions = [
			QueryDecorators\ArStmt::addCondtionCustidRef1($q, $custID)
		];
		$q->where($conditions, 'or');
		return $q;
	}

	/**
	 * Group By Stmt Date
	 * @param Query $q
	 * @return void
	 */
	public static function groupByStmtDate(Query $q) {
		QueryDecorators\ArStmt::groupByStmtDate($q);
	}
}
