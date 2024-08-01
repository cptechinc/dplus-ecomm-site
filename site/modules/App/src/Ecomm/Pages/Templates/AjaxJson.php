<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * AjaxJson
 * Template for the Ajax JSON Page
 */
class AjaxJson extends AbstractTemplate {
	const NAME  = 'ajax-json';
	const LABEL = 'Ajax JSON';
	const FIELDS = ['title'];
	const IS_SINGLE_USE = true;
	const ALLOW_CHILDREN    = true;
	const ALLOW_PAGINATION  = true;
	const ALLOW_URLSEGMENTS = true;
	const ALLOWED_PARENT_TEMPLATES = ['ajax'];
	const CONTENT_TYPE = 'json';
}