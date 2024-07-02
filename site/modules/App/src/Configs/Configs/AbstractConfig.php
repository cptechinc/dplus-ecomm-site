<?php namespace App\Configs\Configs;
// ProcessWire
use ProcessWire\WireArray;
use ProcessWire\WireData;

/**
 * AbstractConfig
 * Container for Config Data
 */
abstract class AbstractConfig extends WireData {
	const FIELDS = [];

	/**
	 * Return List of Config Field data
	 * @return WireArray
	 */
	public function fields() {
		$list = new WireArray();

		foreach (static::FIELDS as $data) {
			$field = new WireData();
			$field->setArray($data);
			$field->value = '';

			if ($field->fieldtype == 'field') {
				$field->value = $this->get($field->name);
			}
			$list->set($field->name, $field);
		}
		return $list;
	}
}