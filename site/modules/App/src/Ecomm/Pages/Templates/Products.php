<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * Products
 * Template for the Products Page
 */
class Products extends AbstractTemplate {
	const NAME  = 'products';
	const LABEL = 'Products';
	const FIELDS = ['title'];
	const IS_SINGLE_USE     = true;
	const ALLOW_CHILDREN    = true;
	const ALLOWED_PARENT_TEMPLATES = [];
	const ALLOWED_CHILD_TEMPLATES  = ['product'];
}