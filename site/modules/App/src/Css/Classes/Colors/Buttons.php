<?php namespace App\Css\Classes\Colors;
// App
use App\Css\Classes\AbstractCssLookup;

/**
 * Buttons
 * Handles Lookup of CSS Button Color classes
 */
class Buttons extends AbstractCssLookup {
	const MAP = [
		'lookup' => 'btn-hotpink',
		'save'   => 'btn-success',
		'submit' => 'btn-success',
		'link'   => 'btn-template-outlined',
		'exit'   => 'btn-warning',
		'delete' => 'btn-danger',
	];

	protected static $instance;
}