<?php namespace App\Ecomm\Pages\Pages;
// App
use App\Pw\Pages\AbstractPageBuilder;

/**
 * Site
 * Installs Site Related Pages
 */
class Site extends AbstractPageBuilder {
	const PAGES = [
		'admin-site' => [
			'name'     => 'admin',
			'title'    => 'Site Administration',
			'template' => 'admin-site',
			'parentSelector'   => '/',
		],
		'admin-site-rebuild' => [
			'name'     => 'rebuild',
			'title'    => 'Site Rebuild',
			'template' => 'admin-site-rebuild',
			'parentSelector'   => '/admin/',
		],
		'ajax' => [
			'name'     => 'ajax',
			'title'    => 'AJAX',
			'template' => 'ajax',
			'parentSelector'   => '/',
		],
		'ajax-json' => [
			'name'     => 'json',
			'title'    => 'JSON',
			'template' => 'ajax-json',
			'parentSelector'   => '/ajax/',
		],
		'config' => [
			'name'     => 'config',
			'title'    => 'Config',
			'template' => 'site-config',
			'parentSelector'   => '/',
		],
		'products' => [
			'name'     => 'products',
			'title'    => 'Products',
			'template' => 'products',
			'parentSelector'   => '/',
		],
	];
}