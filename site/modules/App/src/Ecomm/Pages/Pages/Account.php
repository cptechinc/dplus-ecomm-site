<?php namespace App\Ecomm\Pages\Pages;
// App
use App\Pw\Pages\AbstractPageBuilder;

/**
 * Account
 * Installs Account Related Pages
 */
class Account extends AbstractPageBuilder {
	const PAGES = [
		'login' => [
			'name'     => 'login',
			'title'    => 'Login',
			'template' => 'login',
			'parentSelector'   => '/',
		]
	];
}