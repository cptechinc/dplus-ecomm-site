<?php namespace Dplus\Docm;
// Dplus Models
use Document;
use DocumentQuery as Query;
// ProcessWire
use ProcessWire\WireData;

/**
 * Base QueryWrapper
 */
class StaticDocumentQueryWrapper {
	protected static $columns;

/* =============================================================
	Query Functions
============================================================= */
	/**
	 * Return Query
	 * @return Query
	 */
	public static function query() {
		return Query::create();
	}

	/**
	 * Return Query
	 * @return Query
	 */
	protected static function _query() {
		return Query::create();
	}

	/**
	 * Return Query filtered by Folder, Filename
	 * @param  string $folder
	 * @param  string $filename
	 * @return Query
	 */
	public static function queryFolderFilename($folder, $filename) {
		$q = static::query();
		$q->filterByFolder($folder);
		$q->filterByFilename($filename);
		return $q;
	}

/* =============================================================
	Reads
============================================================= */
	/**
	 * Return Query filtered by Folder, Filename
	 * @param  string $folder
	 * @param  string $filename
	 * @return bool
	 */
	public static function documentExists($folder, $filename) {
		return boolval(static::queryFolderFilename($folder, $filename)->count());
	}

	/**
	 * Return Document filtered by Folder, Filename
	 * @param  string $folder
	 * @param  string $filename
	 * @return Document|false
	 */
	public static function document($folder, $filename) {
		return static::queryFolderFilename($folder, $filename)->findOne();
	}

/* =============================================================
	Supplemental Functions
============================================================= */
	/**
	 * Return Columns
	 * @return WireData
	 */
	public static function getColumns() {
		if (empty(self::$columns) === false) {
			return self::$columns; 
		}
		$columns = new WireData();
		$columns->tag = Document::aliasproperty('tag');
		$columns->reference1 = Document::aliasproperty('reference1');
		$columns->reference2 = Document::aliasproperty('reference2');
		$columns->folder     = Document::aliasproperty('folder');
		self::$columns = $columns;
		return self::$columns;
	}

	/**
	 * Return Filepath for File
	 * @param  Document $file
	 * @return string
	 */
	public static function filepath(Document $file) {
		if (empty($file)) {
			return '';
		}
		return rtrim($file->directory->directory, '/') . '/' . $file->filename;
	}
}