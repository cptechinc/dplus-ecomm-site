<?php namespace Dpay\Db\Tables\Data;
// Pauldro ProcessWire
use Pauldro\ProcessWire\DatabaseTables\Record;

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
class PaymentLink extends Record {
	
}