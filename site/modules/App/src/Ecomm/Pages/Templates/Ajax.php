<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * Ajax
 * Template for the Ajax Page
 */
class Ajax extends AbstractTemplate {
	const NAME  = 'ajax';
	const LABEL = 'Ajax';
	const FIELDS = ['title'];
	const IS_SINGLE_USE = true;
	const ALLOW_CHILDREN    = true;
	const ALLOW_PAGINATION  = true;
	const ALLOW_URLSEGMENTS = true;
	const ALLOWED_PARENT_TEMPLATES = ['home'];
}