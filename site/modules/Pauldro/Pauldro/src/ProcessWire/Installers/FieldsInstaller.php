<?php namespace Pauldro\ProcessWire\Installers;
// ProcessWire
use ProcessWire\WireArray;
use ProcessWire\WireData;
// Pauldro ProcessWire
use Pauldro\ProcessWire\Installers\Fields;

/**
 * FieldsInstaller
 * Installs Fields
 */
class FieldsInstaller {
	const MAP = [
		// 'fieldname' => 'ClassName::class'
		// SiteIcon::NAME      => SiteIcon::class,
		// SiteIconImage::NAME => SiteIconImage::class,
	];

	/**
	 * Install Fields
	 * @return WireArray[array]
	 */
	public static function install() {
		$results = new WireArray();

		foreach (static::MAP as $key => $class) {
			/** @var Fields\AbstractField $class */
			$data = new WireData();
			$data->name    = $key;
			$data->success = $class::install();
			$results->set($key, $data->success);
		}
		return $results;
	}
}