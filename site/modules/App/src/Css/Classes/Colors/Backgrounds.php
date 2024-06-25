<?php namespace App\Css\Classes\Colors;
// App
use App\Css\Classes\AbstractCssLookup;

/**
 * Backgrounds
 * Handles Lookup of CSS Background Color classes
 */
class Backgrounds extends AbstractCssLookup {
	const MAP = [
		'highlight' => 'bg-tweety',
		'delete'    => 'bg-danger',
		'add'       => 'bg-success',
		'update'    => 'bg-warning',
	];

	protected static $instance;
}