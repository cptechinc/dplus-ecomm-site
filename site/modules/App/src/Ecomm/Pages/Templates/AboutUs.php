<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * AboutUs
 * Template for the AboutUs Page
 */
class AboutUs extends AbstractTemplate {
	const NAME  = 'about-us';
	const LABEL = 'About Us';
	const FIELDS = ['title', 'summary'];
	const IS_SINGLE_USE     = true;
}