<?php namespace Pauldro\ProcessWire\Installers\Fields;
// ProcessWire
use ProcessWire\Field;
use ProcessWire\FieldtypeFile;

/**
 * AbstractFieldFile
 * Template for creating / updating file field
 */
class AbstractFieldFile extends AbstractField {
	const TYPE        = 'file';
	const PW_TYPE     = 'file';
	// FILE SPECIFIC ATTRIBUTES
	const OUTPUT_FORMAT = FieldtypeFile::outputFormatAuto;
	const ALLOWED_FILE_EXTENSIONS = [];
	const MAX_NBR_OF_FILES = 0;


	/**
	 * Return new Field with properties set
	 * @return Field
	 */
	public static function newField() {
		$f = parent::newField();
		$f->extensions   = implode(' ', static::ALLOWED_FILE_EXTENSIONS);
		$f->maxFiles     = static::MAX_NBR_OF_FILES;
		$f->outputFormat = static::OUTPUT_FORMAT;
		return $f;
	}
}