<?php namespace App\Ecomm\Pages;
// Pauldro ProcessWire
use Pauldro\ProcessWire\Installers\TemplatesInstaller as ParentInstaller;
// App
use App\Ecomm\Pages\Templates;

/**
 * TemplatesInstaller
 * Installs Templates
 * 
 * @property bool  $installOnlyNew
 * @property array $installed 
 */
class TemplatesInstaller extends ParentInstaller {
	const TEMPLATE_CLASSES = [
		'admin-site'  => Templates\AdminSite::class,
		'login'       => Templates\Login::class,
		'account'     => Templates\Account::class,
		'product'     => Templates\Product::class,
		'products'    => Templates\Products::class,
		'site-conrig' => Templates\SiteConfig::class,
	];
}