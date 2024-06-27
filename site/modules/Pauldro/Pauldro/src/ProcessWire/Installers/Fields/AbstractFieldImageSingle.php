<?php namespace Pauldro\ProcessWire\Installers\Fields;
// ProcessWire
use ProcessWire\FieldtypeFile;

/**
 * AbstractFieldImage
 * Template for creating / updating image field
 */
class AbstractFieldImageSingle extends AbstractFieldImage {
	// FILE SPECIFIC ATTRIBUTES
	const OUTPUT_FORMAT = FieldtypeFile::outputFormatSingle;
	const ALLOWED_FILE_EXTENSIONS = ['gif', 'jpg', 'jpeg', 'png'];
	const MAX_NBR_OF_FILES = 1;
}