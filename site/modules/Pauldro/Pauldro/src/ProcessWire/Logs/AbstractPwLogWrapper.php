<?php namespace Pauldro\ProcessWire\Logs;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireException;


/**
 * AbstractPwLogWrapper
 * Wrapper for handling CRUD operations on one log using WireLog
 * @see WireLog
 */
abstract class AbstractPwLogWrapper extends WireData {
	const LOGNAME = '';
	const LOGLINE_DELIMITER = "\t";
	const FILE_EXTENSION = '.txt';

	/** @var static */
	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	/** @return static */
	public static function instance() {
		if (empty(static::$instance)) {
			$instance = new static();
			static::$instance = $instance;
		}
		return static::$instance;
	}

/* =============================================================
	Reads
============================================================= */
	/**
	 * Retrn Lines from the end of the log
	 * @param  array $options
	 * @return array
	 */
	public function getEntries($options = []) {
		if (array_key_exists('limit', $options) === false) {
			$options['limit'] = 100;
		}
		return $this->log->getLines(static::LOGNAME, $options);
	}

	/**
	 * Return Number of Entries in Log
	 * @return int
	 */
	public function countEntries() {
		return $this->log->getTotalEntries(static::LOGNAME);
	}

/* =============================================================
	Updates
============================================================= */
	/**
	 * Add Line to Log
	 * @param  array|string $data
	 * @return bool
	 */
	public function log($data) {
		$text = $data;

		if (is_array($data)) {
			$text = implode(static::LOGLINE_DELIMITER, $data);
		}
		return $this->log->save(static::LOGNAME, $text, ['showUser' => false]);
	}

/* =============================================================
	Deletes
============================================================= */
	/**
	 * Clear Log File
	 * NOTE: deletes log
	 * @return bool
	 */
	public function clear() {
		return $this->log->delete(static::LOGNAME);
	}

	/**
	 * Prune Log to the last x days
	 * @param  int  $days Number of days to keep
	 * @return int        Number of entries after prune
	 */
	public function prune(int $days = 1) {
		return $this->log->prune(static::LOGNAME, $days);
	}

/* =============================================================
	Filesystem
============================================================= */
	/**
	 * Return Filepath
	 * @return string|false  /path/to/file.txt
	 */
	public function getFilepath() {
		$filepath = '';

		try {
			$filepath = $this->log->getFilename(static::LOGNAME);
		} catch (WireException $e) {
			return false;
		}
		return $filepath;
	}
	
	/**
	 * Copy Log file
	 * @param  string $dir
	 * @param  string $filename  
	 * @param  string $extension
	 * @return bool
	 */
	public function copyTo($dir, $filename = '', $extension = '.txt') {
		$file = $this->getFilepath();
		if ($file === false) {
			return false;
		}
		if (is_dir($dir) === false) {
			return false;
		}
		$dir = rtrim($dir, '/') . '/';
		$filename  = $filename ? $filename : static::LOGNAME;
		$extension = $extension ? $extension : static::FILE_EXTENSION;
		return copy($file, $dir.$filename.$extension);
	}
}