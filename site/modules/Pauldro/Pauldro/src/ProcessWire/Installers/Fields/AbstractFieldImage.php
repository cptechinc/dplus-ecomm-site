<?php namespace Pauldro\ProcessWire\Installers\Fields;
// ProcessWire
use ProcessWire\FieldtypeFile;

/**
 * AbstractFieldImage
 * Template for creating / updating image field
 */
class AbstractFieldImage extends AbstractFieldFile {
	const TYPE        = 'image';
	const PW_TYPE     = 'image';
	// FILE SPECIFIC ATTRIBUTES
	const OUTPUT_FORMAT = FieldtypeFile::outputFormatAuto;
	const ALLOWED_FILE_EXTENSIONS = ['gif', 'jpg', 'jpeg', 'png'];
	const MAX_NBR_OF_FILES = 0;
}