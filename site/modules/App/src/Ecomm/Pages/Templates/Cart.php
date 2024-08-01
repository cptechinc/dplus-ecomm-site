<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * Cart
 * Template for the Cart Site Page
 */
class Cart extends AbstractTemplate {
	const NAME  = 'cart';
	const LABEL = 'Cart';
	const FIELDS = ['title'];
	const IS_SINGLE_USE = true;
	const ALLOW_URLSEGMENTS = true;
	const ALLOWED_PARENT_TEMPLATES = ['home'];
}