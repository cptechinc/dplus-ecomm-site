<?php namespace Pauldro\ProcessWire\Logs;
// ProcessWire
use ProcessWire\WireException;
use ProcessWire\WireLog;
// Pauldro ProcessWire
use Pauldro\ProcessWire\AbstractStaticPwClass;


/**
 * AbstractPwLogWrapper
 * Wrapper for handling CRUD operations on one log using WireLog
 * @see WireLog
 */
class PwLogger extends AbstractStaticPwClass {
	const LOGNAME = '';
	const LOGLINE_DELIMITER = "\t";
	const FILE_EXTENSION = '.txt';
	const DEFAULT_LOG_OPTIONS = ['showUser' => false, 'showURL' => false];

/* =============================================================
	Reads
============================================================= */
	/**
	 * Retrn Lines from the end of the log
	 * @param  string $name      Log Name
	 * @param  array  $options
	 * @return array
	 */
	public static function getEntries($name, $options = []) {
		if (array_key_exists('limit', $options) === false) {
			$options['limit'] = 100;
		}
		return self::pwLog()->getLines($name, $options);
	}

	/**
	 * Return Number of Entries in Log
	 * @param  string $name Log Name
	 * @return int
	 */
	public static function countEntries($name) {
		return self::pwLog()->getTotalEntries($name);
	}

/* =============================================================
	Updates
============================================================= */
	/**
	 * Add Line to Log
	 * @see WireLog::save()
	 * @param  string       $name  Log Name
	 * @param  array|string $data
	 * @param  array        $options  Logging Options
	 * @return bool
	 */
	public static function log($name, $data, $options = []) {
		$text = $data;
		$opts = self::DEFAULT_LOG_OPTIONS;
		$opts = array_merge($opts, $options);

		if (is_array($data)) {
			$text = implode(static::LOGLINE_DELIMITER, $data);
		}
		return self::pwLog()->save($name, $text, $opts);
	}

/* =============================================================
	Deletes
============================================================= */
	/**
	 * Clear Log File
	 * NOTE: deletes log
	 * @param  string  $name  Log Name
	 * @return bool
	 */
	public static function clear($name) {
		return self::pwLog()->delete(static::LOGNAME);
	}

	/**
	 * Prune Log to the last x days
	 * @param  string  $name  Log Name
	 * @param  int     $days  Number of days to keep
	 * @return int            Number of entries after prune
	 */
	public static function prune($name, int $days = 1) {
		return self::pwLog()->prune($name, $days);
	}

/* =============================================================
	Filesystem
============================================================= */
	/**
	 * Return Filepath
	 * @param  string        $name   Log Name
	 * @return string|false          /path/to/file.txt
	 */
	public static function getFilepath($name) {
		$filepath = '';

		try {
			$filepath = self::pwLog()->getFilename($name);
		} catch (WireException $e) {
			return false;
		}
		return $filepath;
	}
	
	/**
	 * Copy Log file
	 * @param  string  $name  Log Name
	 * @param  string  $dir
	 * @param  string  $filename  
	 * @param  string  $extension
	 * @return bool
	 */
	public static function copyTo($name, $dir, $filename = '', $extension = '.txt') {
		$file = self::getFilepath($name);
		if ($file === false) {
			return false;
		}
		if (is_dir($dir) === false) {
			return false;
		}
		$dir = rtrim($dir, '/') . '/';
		$filename  = $filename ? $filename : $name;
		$extension = $extension ? $extension : static::FILE_EXTENSION;
		return copy($file, $dir.$filename.$extension);
	}
	
/* =============================================================
	Filesystem
============================================================= */
	/**
	 * Return Logger
	 * @return WireLog
	 */
	private static function pwLog() {
		return self::pw('log');
	}
}