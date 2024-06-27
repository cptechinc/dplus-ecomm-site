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
	 * RInstall Fields
	 * @return WireArray
	 */
	public static function install() {
		$results = new WireArray();

		foreach (static::MAP as $key => $class) {
			$data = new WireData();
			$data->name = $key;
			$data->success = $class::install();
			$results->set($key, $data);
		}
		return $results;
	}
}