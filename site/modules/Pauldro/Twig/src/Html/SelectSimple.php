<?php namespace Pauldro\Twig\Html;


/**
 * SelectSimple
 * Data Container for Selects
 * 
 * @property string $type        SelectSimple Type (text|number)
 * @property string $inputclass  Base SelectSimple class
 * @property string $name        SelectSimple Name
 * @property string $id          SelectSimple ID
 * @property mixed  $value       SelectSimple Value
 * @property string $size        SelectSimple size (@see self::SIZES)
 * @property array  $addclasses  Additional Classes (One-Dimensional)
 * @property array  $attributes  Attributes (Key-Value)
 */
class SelectSimple extends AbstractElement {
	const CLASS_PREFIX = 'form-control';
	
	const ATTRIBUTES_SKIPVALUE = [
		'readonly',
		'disabled',
		'required',
		'autofocus'
	];

	const SIZES = ['xs', 'sm', 'md', 'lg', 'xl'];

	const DEFAULTS = [
		'inputclass' => self::CLASS_PREFIX,
		'name'       => '',
		'id'         => '',
		'value'      => '',
		'size'       => '',
		'addclasses' => [],
		'attributes' => [],
		'showvalue'   => false,
		'haskeys'     => false,
		'capitalizelabels' => false,
		'useblankoption'   => false,
		'options'     => [],
		'blankoption' => [
			'value'   => '',
			'label'   => ''
		]
	];

	/**
	 * Return id value for SelectSimple
	 * NOTE: Will Return Name as id if blank
	 * @return string
	 */
	public function id() {
		return $this->id ? $this->id : $this->name;
	}

	/**
	 * Return class
	 * NOTE: Takes SelectSimple class and adds additionally supplied classes
	 * @return string
	 */
	public function class() {
		$base  = self::CLASS_PREFIX;
		$classes = [$this->inputclass];

		if (in_array($this->size, self::SIZES)) {
			$classes[] = "$base-$this->size";
		}
		$classes = array_merge($classes, $this->addclasses);
		return trim(implode(' ', $classes));
	}

	/**
	 * Return SelectSimple Value
	 * @return mixed
	 */
	public function value() {
		if ($this->value === null) {
			$this->value = '';
		}
		return $this->value;
	}
}
