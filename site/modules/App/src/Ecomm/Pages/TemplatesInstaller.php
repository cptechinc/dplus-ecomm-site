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
		'ajax'        => Templates\Ajax::class,
		'ajax-json'   => Templates\AjaxJson::class,
		'admin-site'  => Templates\AdminSite::class,
		'admin-site-rebuild' => Templates\AdminSiteRebuild::class,
		'cart'        => Templates\Cart::class,
		'login'       => Templates\Login::class,
		'account'     => Templates\Account::class,
		'product'     => Templates\Product::class,
		'products'    => Templates\Products::class,
		'products-item-groups'    => Templates\ProductsItemGroups::class,
		'products-search'    => Templates\ProductsSearch::class,
		'site-config' => Templates\SiteConfig::class,
	];
}