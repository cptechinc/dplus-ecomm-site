<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * Account
 * Template for the Account Page
 */
class Account extends AbstractTemplate {
	const NAME  = 'account';
	const LABEL = 'Account';
	const FIELDS = ['title'];
	const IS_SINGLE_USE     = true;
	const ALLOW_URLSEGMENTS = true;
}