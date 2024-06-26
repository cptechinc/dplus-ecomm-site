<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * Login
 * Template for the Login Page
 */
class Login extends AbstractTemplate {
	const NAME  = 'login';
	const LABEL = 'Login';
	const FIELDS = ['title'];
	const IS_SINGLE_USE = true;
}