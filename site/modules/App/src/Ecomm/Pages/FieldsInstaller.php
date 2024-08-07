<?php namespace App\Ecomm\Pages;
// Pauldro ProcessWire
use Pauldro\ProcessWire\Installers\FieldsInstaller as ParentInstaller;
// App
use App\Ecomm\Pages\Fields;

/**
 * FieldsInstaller
 * Installs Fields
 * 
 * @property bool  $installOnlyNew
 * @property array $installed 
 */
class FieldsInstaller extends ParentInstaller {
	const MAP = [
		Fields\ItemDescription::NAME  => Fields\ItemDescription::class,
		Fields\DisplayName::NAME      => Fields\DisplayName::class,
		Fields\Dplusid::NAME          => Fields\Dplusid::class,
		Fields\Itemid::NAME           => Fields\Itemid::class,
		Fields\SiteIcon::NAME         => Fields\SiteIcon::class,
		Fields\SiteIconImage::NAME    => Fields\SiteIconImage::class,
		Fields\SiteLogoImage::NAME    => Fields\SiteLogoImage::class	
	];
}