<?php namespace Dplus\Docm\QueryDecorators;
// Dplus Models
use Document;
use DocumentQuery as Query;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Docm\StaticDocumentQueryWrapper;



/**
 * ArInvoice
 * Query Decorator for AR Invoices
 */
class ArInvoice extends StaticDocumentQueryWrapper {
	const TAG = 'AR';


	/**
	 * Add Query Condition for AR Invoice # for Ref1
	 * @param  DocumentQuery $q     Query
	 * @param  string        $ordn  Sales Order #
	 * @param  string        $name  Condition Name
	 * @return string
	 */
	public static function addCondtionArInvnbrRef1(Query $q, $ordn, $name = 'ar_invoices') {
		$columns = self::getColumns();
		$q->condition('tag_invoices', "Document.{$columns->tag} = ?", self::TAG);
		$q->condition('reference1_invoices', "Document.{$columns->reference1} = ?", $ordn);
		$q->combine(['tag_invoices', 'reference1_invoices'], 'AND', $name);
		return $name;
	}
}