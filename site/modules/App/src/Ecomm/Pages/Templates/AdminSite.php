<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * AdminSite
 * Template for the Admin Site Page
 */
class AdminSite extends AbstractTemplate {
	const NAME  = 'admin-site';
	const LABEL = 'Site Administration';
	const FIELDS = ['title'];
	const IS_SINGLE_USE = true;
	const ALLOW_CHILDREN    = true;
	const ALLOW_PAGINATION  = true;
	const ALLOW_URLSEGMENTS = true;
	const ALLOWED_CHILD_TEMPLATES = ['admin-site-rebuild'];
}