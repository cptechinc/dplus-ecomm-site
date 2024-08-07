<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * ProductsItemGroups
 * Template for the Product Item Groups Page
 */
class ProductsItemGroups extends AbstractTemplate {
	const NAME  = 'products-item-groups';
	const LABEL = 'Products Item Groups';
	const FIELDS = ['title'];
	const IS_SINGLE_USE     = true;
	const ALLOW_CHILDREN    = true;
	const ALLOWED_PARENT_TEMPLATES = ['products'];
	const ALLOWED_CHILD_TEMPLATES  = ['products-item-group'];
}