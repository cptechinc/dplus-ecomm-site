<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * AdminSiteRebuild
 * Template for the Admin Site Rebuild Page
 */
class AdminSiteRebuild extends AbstractTemplate {
	const NAME  = 'admin-site-rebuild';
	const LABEL = 'Site Rebuild';
	const FIELDS = ['title'];
	const IS_SINGLE_USE = true;
	const ALLOW_CHILDREN    = true;
	const ALLOW_PAGINATION  = true;
	const ALLOW_URLSEGMENTS = true;
	const ALLOWED_PARENT_TEMPLATES = ['admin-site'];
	const CONTENT_TYPE = 'json';
}