<?php namespace Dplus\Docm\Finders;
// Propel ORM Library
	// use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
// Dplus Model
use DocumentQuery as Query, Document as Record;
// Dplus
use Dplus\Database\Tables\SalesOrder as SoTable;
// Dplus
use Dplus\Docm\StaticDocumentQueryWrapper;
use Dplus\Docm\QueryDecorators;

/**
 * SalesOrder
 * Decorator for DocumentQuery to find Documents in Database related to Sales Order #
 */
class SalesOrder extends StaticDocumentQueryWrapper {
/* =============================================================
	Read Functions
============================================================= */
	/**
	 * Return Documents related to Sales Order #
	 * @param  string $ordn  Sales Order #
	 * @return ObjectCollection|Record[]
	 */
	public static function find($ordn) {
		$q = self::query();
		self::filterSales($q, SoTable::padOrdernumber($ordn));
		return $q->find();
	}

	/**
	 * Return the number of Documents related to Sales Order #
	 * @param  string $ordn  Sales Order #
	 * @return int
	 */
	public static function count($ordn) {
		$q = self::query();
		self::filterSales($q, SoTable::padOrdernumber($ordn));
		return $q->count();
	}

/* =============================================================
	Query Decorator Functions
============================================================= */
	/**
	 * Adds Filter Conditions to the Documents Query
	 * to find Documents associated with a Sales Order
	 * @param  Query $q     Query
	 * @param  string       $ordn  Sales Order #
	 * @return Query
	 */
	public static function filterSales(Query $q, $ordn) {
		$ordn = SoTable::padOrdernumber($ordn);

		$conditions = [
			QueryDecorators\ArInvoice::addCondtionArInvnbrRef1($q, $ordn)
		];
		$q->where($conditions, 'or');
		return $q;
	}
}
