<?php namespace Pauldro\Twig\Html;


/**
 * InputGroup
 * Data Container for InputGroup
 * 
 * @property string $id           ID
 * @property string $type         InputGroup Type (text|number)
 * @property string $size         InputGroup size (@see self::SIZES)
 * @property array  $addclasses   Additional Classes (One-Dimensional)
 * @property array  $attributes   Attributes (Key-Value)
 * @property array  $input        Input Data
 * @property array  $button       Button Data
 * @property array  $span         Span Data
 */
class InputGroup extends AbstractElement {
	const CLASS_PREFIX = 'input-group';
	const ATTRIBUTES_SKIPVALUE = [
		'readonly',
		'disabled',
	];

	const SIZES = ['xs', 'sm', 'md', 'lg', 'xl'];
	const TYPES = ['prepend', 'append'];

	const DEFAULTS = [
		'id'         => '',
		'type'       => 'prepend',
		'size'       => '',
		'addclasses' => [],
		'attributes' => [],
		'input'      => [],
		'button'     => [],
		'span'       => [],
	];

	/**
	 * Return id value for InputGroup
	 * NOTE: Will Return Name as id if blank
	 * @return string
	 */
	public function id() {
		return $this->id;
	}

	/**
	 * Return class
	 * NOTE: Takes InputGroup class and adds additionally supplied classes
	 * @return string
	 */
	public function class() {
		$base  = self::CLASS_PREFIX;
		$classes = [self::CLASS_PREFIX];

		if (in_array($this->size, self::SIZES)) {
			$classes[] = "$base-$this->size";
		}
		$classes = array_merge($classes, $this->addclasses);
		return trim(implode(' ', $classes));
	}

	/**
	 * Set Properties from Array (key-value)
	 * @param  array $attributes
	 * @return void
	 */
	public function setFromArray(array $array) {
		parent::setFromArray($array);

		if (array_key_exists('attributes', $array)) {
			$attributes = $array['attributes'];

			if (array_key_exists('disabled', $attributes)) {
				if ($this->input) {
					$input = $this->input;
					$input['attributes']['disabled']  = true;
					$this->input = $input;
				}

				if ($this->button) {
					$button = $this->button;
					$button['attributes']['disabled'] = true;
					$this->button = $button;
				}
			}
		}
	}
}
