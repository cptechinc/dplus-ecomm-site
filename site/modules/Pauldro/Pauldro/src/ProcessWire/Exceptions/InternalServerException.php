<?php namespace Pauldro\ProcessWire\Exceptions;

use ProcessWire\WireException;

class InternalServerException extends WireException {
	protected $code = 500;
}