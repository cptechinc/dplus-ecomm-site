<?php namespace Pauldro\ProcessWire\Installers\Fields;
// ProcessWire
use ProcessWire\Field;
// use ProcessWire\FieldtypeTextarea;

/**
 * AbstractFieldUrl
 * Template for creating / updating URL fields
 */
class AbstractFieldUrl extends AbstractField {
	const TYPE        = 'url';
	const PW_TYPE     = 'url';

	/**
	 * Set Properties of Field
	 * @param  Field $f
	 * @return void
	 */
	protected static function setFieldProperties(Field $f) {
		$f->type  = 'URL';
		$f->name  = static::NAME;
		$f->label = static::LABEL;
		$f->description = static::DESCRIPTION;
	}
}