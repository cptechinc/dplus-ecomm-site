<?php namespace Pauldro\ProcessWire\Installers\Fields;
// ProcessWire
	// use ProcessWire\Field;
use ProcessWire\FieldtypeTextarea;
	

/**
 * AbstractFieldTextareaHtml
 * Template for creating / updating textarea fields that have HTML markup
 */
class AbstractFieldTextareaHtml extends AbstractFieldTextarea {
	const TYPE        = 'textarea';
	const PW_TYPE     = 'textarea';
	const INPUT_FIELD_CLASS = 'InputfieldCKEditor';
	const CONTENT_TYPE = FieldtypeTextarea::contentTypeHTML;
}