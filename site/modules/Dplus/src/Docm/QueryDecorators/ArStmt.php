<?php namespace Dplus\Docm\QueryDecorators;
// Dplus Models
use Document as Record;
use DocumentQuery as Query;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Docm\StaticDocumentQueryWrapper;



/**
 * ArStmt
 * Query Decorator for AR Statements
 */
class ArStmt extends StaticDocumentQueryWrapper {
	const FOLDER = 'ARSTMT';

	/**
	 * Add Query Condition for CustID for Ref1
	 * @param  Query $q       Query
	 * @param  string        $custID  Sales Order #
	 * @param  string        $name    Condition Name
	 * @return string
	 */
	public static function addCondtionCustidRef1(Query $q, $custID, $name = 'arstmt') {
		$columns = self::getColumns();
		$q->condition('folder', "Document.{$columns->folder} = ?", self::FOLDER);
		$q->condition('reference1_custid', "Document.{$columns->reference1} = ?", $custID);
		$q->combine(['folder', 'reference1_custid'], 'AND', $name);
		return $name;
	}

	/**
	 * Group Query by Statement DAte
	 * @param  Query $q
	 * @return void
	 */
	public static function groupByStmtDate(Query $q) {
		$q->groupBy([Record::aliasproperty('reference1'), Record::aliasproperty('reference2')]);
	}
}