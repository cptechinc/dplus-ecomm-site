<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * Product
 * Template for the Products Page
 */
class Product extends AbstractTemplate {
	const NAME  = 'product';
	const LABEL = 'Product';
	const FIELDS = ['title', 'itemid', 'itemdescription'];
	const IS_SINGLE_USE     = false;
	const ALLOW_CHILDREN    = false;
	const ALLOWED_PARENT_TEMPLATES = ['products'];
	const ALLOWED_CHILD_TEMPLATES  = [];
}