<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * ProductsSearch
 * Template for the ProductsSearch Page
 */
class ProductsSearch extends AbstractTemplate {
	const NAME  = 'products-search';
	const LABEL = 'Search Products';
	const FIELDS = ['title'];
	const IS_SINGLE_USE     = true;
	const ALLOW_CHILDREN    = false;
	const ALLOWED_PARENT_TEMPLATES = ['products'];
	const ALLOW_PAGINATION  = true;
}