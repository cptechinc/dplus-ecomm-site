<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * ProductsItemGroup
 * Template for the Products Item Group  Page
 */
class ProductsItemGroup extends AbstractTemplate {
	const NAME  = 'products-item-group';
	const LABEL = 'Products Item Group';
	const FIELDS = ['title', 'dplusid', 'dplusdescription'];
	const IS_SINGLE_USE     = false;
	const ALLOW_CHILDREN    = false;
	const ALLOWED_PARENT_TEMPLATES = ['products-item-groups'];
	const ALLOWED_CHILD_TEMPLATES  = [];
}