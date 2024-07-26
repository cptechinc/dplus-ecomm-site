<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * SiteConfig
 * Template for the SiteConfig Page
 */
class SiteConfig extends AbstractTemplate {
	const NAME  = 'site-config';
	const LABEL = 'Site Config';
	const FIELDS = ['title', 'displayname', 'siteicon', 'siteiconimage', 'sitelogoimage'];
	const IS_SINGLE_USE = true;
}