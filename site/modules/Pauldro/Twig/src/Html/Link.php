<?php namespace Pauldro\Twig\Html;


/**
 * Link
 * Data Container for Link
 * 
 * @property string $href         URL
 * @property array  $addclasses   Additional Classes (One-Dimensional)
 * @property array  $attributes   Attributes (Key-Value)
 */
class Link extends AbstractElement {
	const DEFAULTS = [
		'href'       => '#',
		'addclasses' => [],
		'attributes' => [],
		'text',
	];

	/**
	 * Return Class for Link
	 * @return string
	 */
	public function class() {
		return trim(implode(' ', $this->addclasses));
	}
}
