<?php namespace App\Ecomm\Pages\Fields;
// Pauldro ProcessWire
use Pauldro\ProcessWire\Installers\Fields\AbstractFieldImageSingle;

/**
 * SiteIcon
 * creates / updates siteicon field
 */
class SiteIcon extends AbstractFieldImageSingle {
	const NAME        = 'siteicon';
	const LABEL       = 'Site Icon';
	const DESCRIPTION = 'Icon for app / function';
	const NOTES       = '';
	const TYPE        = 'image';
	const PW_TYPE     = 'image';
	
	// FILE SPECIFIC ATTRIBUTES
	const ALLOWED_FILE_EXTENSIONS = ['ico'];
	const MAX_NBR_OF_FILES = 1;
}