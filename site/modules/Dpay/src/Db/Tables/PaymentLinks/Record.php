<?php namespace Dpay\Db\Tables\PaymentLinks;
// Pauldro ProcessWire
use Pauldro\ProcessWire\DatabaseTables\Record as AbstractRecord;

/**
 * PaymentLinks\Record
 * Container for Payment Link Data
 * 
 * @property int     $id         Record ID
 * @property string  $timestamp  Timestamp
 * @property int     $ordernbr   Order Number
 * @property string  $custid     Customer ID
 * @property string  $linkid     Link ID (not used)
 * @property string  $url        URL
 */
class Record extends AbstractRecord {
	
}