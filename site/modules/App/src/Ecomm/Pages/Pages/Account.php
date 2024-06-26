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
		],
		'account' => [
			'name'     => 'account',
			'title'    => 'Your Account',
			'template' => 'account',
			'parentSelector'   => '/',
		],
	];
}