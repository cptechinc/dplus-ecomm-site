<?php namespace Pauldro\Twig\Html;


/**
 * Input
 * Data Container for Inputs
 * 
 * @property string $type        Input Type (text|number)
 * @property string $inputclass  Base Input class
 * @property string $name        Input Name
 * @property string $id          Input ID
 * @property mixed  $value       Input Value
 * @property string $size        Input size (@see self::SIZES)
 * @property array  $addclasses  Additional Classes (One-Dimensional)
 * @property array  $attributes  Attributes (Key-Value)
 */
class Input extends AbstractElement {
	const CLASS_PREFIX = 'form-control';
	
	const ATTRIBUTES_SKIPVALUE = [
		'readonly',
		'disabled',
		'required',
		'autofocus',
		'checked'
	];

	const SIZES = ['xs', 'sm', 'md', 'lg', 'xl'];

	const DEFAULTS = [
		'type'       => 'text',
		'inputclass' => 'form-control',
		'name'       => '',
		'id'         => '',
		'value'      => '',
		'size'       => '',
		'addclasses' => [],
		'attributes' => []
	];

	/**
	 * Return id value for Input
	 * NOTE: Will Return Name as id if blank
	 * @return string
	 */
	public function id() {
		return $this->id ? $this->id : $this->name;
	}

	/**
	 * Return class
	 * NOTE: Takes Input class and adds additionally supplied classes
	 * @return string
	 */
	public function class() {
		$base  = self::CLASS_PREFIX;
		$class = $this->inputclass;

		if (in_array($this->size, self::SIZES)) {
			$class .= " $base-$this->size";
		}
		$class .= ' ' . implode(' ', $this->addclasses);
		return trim($class);
	}

	/**
	 * Return Input Value
	 * @return mixed
	 */
	public function value() {
		if ($this->value === null) {
			$this->value = '';
		}
		return $this->value;
	}
}
