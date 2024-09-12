<?php namespace App\Util\Emails;
// Twig
use Twig\Loader\FilesystemLoader, Twig\Environment;
// Processwire
use ProcessWire\WireData;
// Pauldro Twig
use Pauldro\Twig\TwigDecorator;

/**
 * Twig
 * Autoloads Twig
 * 
 * @property  FileSystemLoader $loader
 * @property  Environment      $twig
 */
class Twig extends WireData {
	public function __construct() {
		$this->loader = false;
		$this->twig   = false;
	}

	/**
	 * Initialize Twig
	 * @return bool
	 */
	public function initTwig() {
		if (empty($this->loader) === false || empty($this->twig) === false) {
			return true;
		}
		$this->loader = new FilesystemLoader(__DIR__ . '/templates/');
		$this->twig   = $this->createTwig();
		return true;
	}

	/**
	 * Return Twig Environment
	 * @return Environment
	 */
	public function createTwig() {
		$twig = new Environment($this->loader, [
			'cache'       => __DIR__ . '/templates/cache/',
			'auto_reload' => true,
			'debug'       => boolval($this->debug)
		]);
		TwigDecorator::decorate($twig, $this->loader);
		return $twig;
	}
}
