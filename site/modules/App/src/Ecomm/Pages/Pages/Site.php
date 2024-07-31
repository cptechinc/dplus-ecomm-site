<?php namespace App\Ecomm\Pages\Pages;
// App
use App\Pw\Pages\AbstractPageBuilder;

/**
 * Site
 * Installs Site Related Pages
 */
class Site extends AbstractPageBuilder {
	const PAGES = [
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