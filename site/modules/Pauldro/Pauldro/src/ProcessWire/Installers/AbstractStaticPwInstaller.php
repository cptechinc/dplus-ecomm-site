<?php namespace Pauldro\ProcessWire\Installers;
// Pauldro ProcessWire
use Pauldro\ProcessWire\AbstractStaticPwClass;

/**
 * AbstractStaticPwInstaller
 * Template for Installing PW components
 */
abstract class AbstractStaticPwInstaller extends AbstractStaticPwClass {
	/**
	 * Install PW components
	 * @return bool
	 */
	public static function install() {
		return static::_install();
	}

	/**
	 * Install PW Components
	 * @return bool
	 */
	abstract protected static function _install();
}