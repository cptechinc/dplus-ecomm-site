<?php namespace Pauldro\Twig\Html;


/**
 * Datepicker
 * Data Container for Datepicker
 * 
 * @property string $id           ID
 * @property string $type         Datepicker Type (text|number)
 * @property string $size         Datepicker size (@see self::SIZES)
 * @property array  $addclasses   Additional Classes (One-Dimensional)
 * @property array  $attributes   Attributes (Key-Value)
 * @property array  $input        Input Data
 * @property array  $button       Button Data
 * @property array  $span         Span Data
 */
class DatePicker extends InputGroup {
	const CLASS_GROUP = 'datepicker';
	const CLASS_INPUT = 'date-input';
	const DEFAULTS = [
		'type'       => 'append',
		'size'       => '',
		'addclasses' => [],
		'attributes' => [],
		'input'      => [],
		'button'     => [],
		'span'       => [],
	];

	/**
	 * Return class
	 * NOTE: Takes Datepicker class and adds additionally supplied classes
	 * @return string
	 */
	public function class() {
		$classes = $this->addclasses;
		$classes[] = self::CLASS_GROUP;
		$this->addclasses = $classes;
		return parent::class();
	}

	/**
	 * Set Properties from Array (key-value)
	 * @param  array $attributes
	 * @return void
	 */
	public function setFromArray(array $array) {
		parent::setFromArray($array);

		$input = $this->input;
		$classes = array_key_exists('addclasses', $this->input) ? $this->input['addclasses'] : [];
		$classes[] = self::CLASS_INPUT;
		$input['addclasses'] = $classes;
		$this->input = $input;
	}
}
