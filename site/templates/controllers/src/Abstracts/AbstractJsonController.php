<?php namespace Controllers\Abstracts;
// ProcessWire
use ProcessWire\WireData;

/**
 * AbstractJsonController
 * Template class to handle JSON Requests
 */
abstract class AbstractJsonController extends AbstractController {
	public static function test() {
		return 'test';
	}
}