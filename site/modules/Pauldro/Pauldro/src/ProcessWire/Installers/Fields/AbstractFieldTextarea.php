<?php namespace Pauldro\ProcessWire\Installers\Fields;
// ProcessWire
use ProcessWire\Field;
use ProcessWire\FieldtypeTextarea;

/**
 * AbstractFieldTextarea
 * Template for creating / updating textarea fields
 */
class AbstractFieldTextarea extends AbstractField {
	const TYPE        = 'textarea';
	const PW_TYPE     = 'textarea';
	const INPUT_FIELD_CLASS = 'InputfieldTextarea';
	const CONTENT_TYPE = FieldtypeTextarea::contentTypeUnknown;

	/**
	 * Set Properties of Field
	 * @param  Field $f
	 * @return void
	 */
	protected static function setFieldProperties(Field $f) {
		parent::setFieldProperties($f);
		$f->inputfieldClass = static::INPUT_FIELD_CLASS;
		$f->contentType = static::CONTENT_TYPE;
	}
}