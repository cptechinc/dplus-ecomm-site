<?php namespace App\Ecomm\Pages\Templates;
// App
use App\Pw\Templates\AbstractTemplate;

/**
 * ContactUs
 * Template for the ContactUs Page
 */
class ContactUs extends AbstractTemplate {
	const NAME  = 'contact-us';
	const LABEL = 'Contact Us';
	const FIELDS = ['title', 'summary', 'body', 'emails', 'phonenumbers'];
	const IS_SINGLE_USE     = true;
}