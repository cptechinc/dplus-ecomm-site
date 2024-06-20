<?php namespace Pauldro\Twig\Html;


/**
 * Button
 * Data Container for Button
 * 
 * @property string $type        Button Type (button|submit)
 * @property string $baseclass    Base Button class
 * @property string $colorclass   Color Class
 * @property string $size         Button size (@see self::SIZES)
 * @property array  $addclasses   Additional Classes (One-Dimensional)
 * @property array  $attributes   Attributes (Key-Value)
 */
class Button extends AbstractElement {
	const CLASS_PREFIX = 'btn';
	const ATTRIBUTES_SKIPVALUE = [
		'readonly',
		'disabled',
	];

	const SIZES = ['xs', 'sm', 'md', 'lg', 'xl'];

	const DEFAULTS = [
		'type'       => 'button',
		'baseclass'  => 'btn',
		'colorclass' => 'btn-primary',
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
	 * NOTE: Takes Button class and adds additionally supplied classes
	 * @return string
	 */
	public function class() {
		$base  = self::CLASS_PREFIX;
		$classes = [$this->baseclass];

		if (in_array($this->size, self::SIZES)) {
			$classes[] = "$base-$this->size";
		}
		$classes[] = $this->colorclass();
		$classes = array_merge($classes, $this->addclasses);
		return trim(implode(' ', $classes));
	}
}
