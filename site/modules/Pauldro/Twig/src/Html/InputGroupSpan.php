<?php namespace Pauldro\Twig\Html;


/**
 * InputGroupSpan
 * Data Container for InputGroupSpan
 * 
 * @property string $baseclass    Base InputGroupSpan class
 * @property string $colorclass   Color Class
 * @property string $size         InputGroupSpan size (@see self::SIZES)
 * @property array  $addclasses   Additional Classes (One-Dimensional)
 * @property array  $attributes   Attributes (Key-Value)
 * @property string $text         Text
 */
class InputGroupSpan extends AbstractElement {
	const CLASS_PREFIX = 'input-group-text';
	const ATTRIBUTES_SKIPVALUE = [
		'readonly',
		'disabled',
	];

	const SIZES = ['xs', 'sm', 'md', 'lg', 'xl'];

	const DEFAULTS = [
		'baseclass'  => 'input-group-text',
		'colorclass' => '',
		'size'       => '',
		'addclasses' => [],
		'attributes' => []
	];

	/**
	 * Return Color Class
	 * @return string
	 */
	public function colorclass() {
		return self::CLASS_PREFIX  . '-' . ltrim($this->colorclass, self::CLASS_PREFIX . '-');
	}

	/**
	 * Return class
	 * NOTE: Takes InputGroupSpan class and adds additionally supplied classes
	 * @return string
	 */
	public function class() {
		$classes = [$this->baseclass];

		if (in_array($this->size, self::SIZES)) {
			$classes[] = "btn-$this->size";
		}
		$classes[] = $this->colorclass();
		$classes = array_merge($classes, $this->addclasses);
		return trim(implode(' ', $classes));
	}
}
