<?php namespace App\Ecomm\Pages\Fields;
// Pauldro ProcessWire
use Pauldro\ProcessWire\Installers\Fields\AbstractFieldImageSingle;

/**
 * SiteIconImage
 * creates / updates siteiconimage field
 */
class SiteIconImage extends AbstractFieldImageSingle {
	const NAME        = 'siteiconimage';
	const LABEL       = 'Site Icon Image';
	const DESCRIPTION = 'Icon for app / function';
	const NOTES       = '';
	const TYPE        = 'image';
	const PW_TYPE     = 'image';
	
	// FILE SPECIFIC ATTRIBUTES
	const ALLOWED_FILE_EXTENSIONS = ['png'];
	const MAX_NBR_OF_FILES = 1;
}