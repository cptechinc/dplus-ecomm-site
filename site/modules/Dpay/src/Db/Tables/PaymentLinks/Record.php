<?php namespace Dpay\Db\Tables\PaymentLinks;
// Pauldro ProcessWire
use Pauldro\ProcessWire\DatabaseTables\Record as AbstractRecord;

/**
 * Payment Link
 * Container for Payment Link Data
 * 
 * @property int     $id         Record ID
 * @property string  $timestamp  Timestamp
 * @property int     $ordn       Order Number
 * @property string  $linkid     Link ID (not used)
 * @property string  $url        URL
 */
class Record extends AbstractRecord {
	
}