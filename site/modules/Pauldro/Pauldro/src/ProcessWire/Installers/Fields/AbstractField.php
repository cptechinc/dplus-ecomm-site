<?php namespace Pauldro\ProcessWire\Installers\Fields;
// ProcessWire
use ProcessWire\Field;
use ProcessWire\ProcessWire;

/**
 * AbstractField
 * Template for creating / updating field
 * 
 * @static ProcessWire $pw ProcesWire Instance
 */
abstract class AbstractField {
	const NAME        = '';
	const LABEL       = '';
	const DESCRIPTION = '';
	const NOTES       = '';
	const TYPE        = 'text';
	const PW_TYPE     = 'text';

	protected static $pw;

	/**
	 * Install Field
	 * @return bool
	 */
	public static function install() {
		$f =  static::fieldFromDatabase();
		if (empty($f)) {
			$f = static::newField();
		}
		static::setFieldProperties($f);
		return $f->save();
	}

	/**
	 * Return Field from Database
	 * @return Field|null
	 */
	public static function fieldFromDatabase() {
		return self::pw('fields')->get(static::NAME);
	}

	/**
	 * Return new Field with properties set
	 * @return Field
	 */
	public static function newField() {
		$f = new Field();
		return $f;
	}

	/**
	 * Set Properties of Field
	 * @param  Field $f
	 * @return void
	 */
	protected static function setFieldProperties(Field $f) {
		$f->type = ucfirst(static::PW_TYPE);
		$f->name = static::NAME;
		$f->label = static::LABEL;
		$f->description = static::DESCRIPTION;
	}

	/**
	 * Return the current ProcessWire Wire Instance
	 * @param  string            $var   Wire Object
	 * @return ProcessWire|mixed
	 */
	protected static function pw($var = '') {
		if (empty(self::$pw)) {
			self::$pw = ProcessWire::getCurrentInstance();
		}
		return $var ? self::$pw->wire($var) : self::$pw;
	}
}