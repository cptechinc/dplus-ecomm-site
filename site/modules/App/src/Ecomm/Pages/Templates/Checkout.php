<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * Checkout
 * Template for the Checkout Site Page
 */
class Checkout extends AbstractTemplate {
	const NAME  = 'checkout';
	const LABEL = 'Checkout';
	const FIELDS = ['title'];
	const IS_SINGLE_USE = true;
	const ALLOW_URLSEGMENTS = true;
	const ALLOWED_PARENT_TEMPLATES = ['home'];
}