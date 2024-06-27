<?php namespace App\Ecomm\Pages\Fields;
// Pauldro ProcessWire
use Pauldro\ProcessWire\Installers\Fields\AbstractFieldImageSingle;
/**
 * iteLogoImage
 * creates / updates sitelogoimage field
 */
class SiteLogoImage extends AbstractFieldImageSingle {
	const NAME        = 'sitelogoimage';
	const LABEL       = 'Site Logo Image';
	const DESCRIPTION = 'Logo for Site';
	const NOTES       = '';
	const TYPE        = 'image';
	const PW_TYPE     = 'image';
	
	// FILE SPECIFIC ATTRIBUTES
	const ALLOWED_FILE_EXTENSIONS = ['png'];
	const MAX_NBR_OF_FILES = 1;
}