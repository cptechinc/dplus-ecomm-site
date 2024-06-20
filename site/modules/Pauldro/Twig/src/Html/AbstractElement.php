<?php namespace Pauldro\Twig\Html;
// ProcessWire
use ProcessWire\WireData;

abstract class AbstractElement extends WireData {
	const ATTRIBUTES_SKIPVALUE = [];
	const DEFAULTS = [];

	public function __construct() {
		foreach (static::DEFAULTS as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * Return class
	 * @return string
	 */
	abstract public function class();


	/**
	 * Set Properties from Object Values
	 * @param  stdClass $obj
	 * @return void
	 */
	public function setFromObj($obj) {
		$attributes = get_object_vars($obj);
		$this->setFromArray($attributes);
	}

	/**
	 * Set Properties from Array (key-value)
	 * @param  array $attributes
	 * @return void
	 */
	public function setFromArray(array $attributes) {
		foreach ($attributes as $attribute => $value) {
			if (array_key_exists($attribute, $this->data)) {
				$this->$attribute = $value;
			}
		}
	}

	/**
	 * Return attributes string
	 * @return string    e.g. placeholder="{}" max="{}" disabled
	 */
	public function attributes() {
		$attr = [];
		foreach ($this->attributes as $key => $value) {
			$val = $this->attributeValue($key, $value);

			if (in_array($key, static::ATTRIBUTES_SKIPVALUE) === false) {
				$attr[] = "$key=\"$val\"";
				continue;
			}
			$attr[] = $val === true ? "$key=\"$key\"" : '';
		}
		return trim(implode(' ', array_filter($attr)));
	}

	/**
	 * Return Parsed Attribute value
	 * @param  string     $attr
	 * @param  mixed|bool $value
	 * @return mixed|bool
	 */
	public function attributeValue($attr, $value) {
		if (in_array($attr, static::ATTRIBUTES_SKIPVALUE) === false) {
			return $value;
		}
		if (is_bool($value)) {
			return $value;
		}
		return $value == 'true';
	}
}