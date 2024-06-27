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
		'login'       => Templates\Login::class,
		'account'     => Templates\Account::class,
		'site-conrig' => Templates\SiteConfig::class
	];
}