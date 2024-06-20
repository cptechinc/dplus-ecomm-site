<?php namespace Pauldro\ProcessWire\DatabaseQuery;

/**
 * DatabaseQueryCreateView
 *
 * A wrapper for CREATE View SQL queries.
 * 
 * @property array  $view
 * @property array  $as
 * @property string $comment Comments for query
 * 
 * @method $this view($sql)
 * @method $this as($sql)
 */
class DatabaseQueryCreateView extends AbstractDatabaseQuery {
	/**
	 * Setup the components of a Create Table query
	 */
	public function __construct() {
		parent::__construct();
		$this->addQueryMethod('view', ' CREATE VIEW `', '', '`');
		$this->addQueryMethod('as', ' AS ', '');
		$this->set('comment', ''); 
	}

	/**
	 * Return the resulting SQL ready for execution with the database
	 */
	public function getQuery() {
		$sql  = trim($this->getQueryMethod('view'));
		$sql .= PHP_EOL;
		$sql .= trim($this->getQueryMethod('as'));
		$sql .= PHP_EOL;

		if($this->get('comment') && $this->wire('config')->debug) {
			// NOTE: PDO thinks ? and :str param identifiers in /* comments */ are real params
			// so we str_replace them out of the comment, and only support comments in debug mode
			$comment = str_replace(array('*/', '?', ':'), '', $this->comment); 
			$sql .= "/* $comment */";
		}
		return $sql; 
	}
}

