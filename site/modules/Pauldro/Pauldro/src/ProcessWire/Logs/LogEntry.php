<?php namespace Pauldro\ProcessWire\Logs;
// ProcessWire
use Pauldro\ProcessWire\DatabaseTables\Record;

/**
 * LogEntry
 * Base Class for LogTable Data
 */
class LogEntry extends Record {
	const LOGGING_CLASS = '\\Pauldro\\ProcessWire\\Logs\\AbstractLogTable';
	
	/**
	 * Unpack JSON Data
	 */
	public function init() {

	}
}