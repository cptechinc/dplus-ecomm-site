<?php namespace Mvc\Controllers;
// ProcessWire
use ProcessWire\Config as PwConfig;
use ProcessWire\Input;
use ProcessWire\Page;
use ProcessWire\Pages;
use ProcessWire\ProcessWire;
use ProcessWire\Roles;
use ProcessWire\Sanitizer;
use ProcessWire\Session;
use ProcessWire\User;
use ProcessWire\Users;
use ProcessWire\WireData;
// Pauldro ProcessWire
use Pauldro\ProcessWire\FileHasher;
// App
use App\Urls\PurlPaginator;

/**
 * AbstractController
 * Template for MVc Controllers
 */
abstract class AbstractController extends WireData {
	/** @var ProcessWire */
	private static $pw;

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return the current ProcessWire Wire Instance
	 * @param  string            $var   Wire Object
	 * @return ProcessWire|mixed
	 */
	public static function pw($var = '') {
		if (empty(self::$pw)) {
			self::$pw = ProcessWire::getCurrentInstance();
		}
		return $var ? self::$pw->wire($var) : self::$pw;
	}

	/**
	 * Return FileHasher
	 * @return FileHasher
	 */
	public static function getFileHasher() {
		return FileHasher::instance();
	}

	/**
	 * Sanitize Input Data
	 * @param  WireData $data  Input Data
	 * @param  array   $fields ['fieldnaame|method']
	 * @return WireData
	 */
	public static function sanitizeParametersShort(WireData $data, $fields) {
		foreach ($fields as $param) {
			// Split param: Format is name|sanitizer method
			$arr = explode('|', $param);
			$name = $arr[0];
			if (array_key_exists(1, $arr) == false) {
				$arr[1] = 'text';
			}
			$method = $arr[1];
			$data->$name = self::sanitizeByMethod($data->$name, $method);
		}
		return $data;
	}

	/**
	 * Parse Sanitizer Method Name
	 * @param  string $method
	 * @return string
	 */
	private static function parseSanitizerMethod($method) {
		/** @var Sanitizer */
		$sanitizer = self::pw('sanitizer');
		$allMethods = $sanitizer->getAll(false);

		if (in_array($method, $allMethods)) {
			return $method;
		}

		if (method_exists($sanitizer, $method)) {
			return $method;
		}

		if ($sanitizer->hooks->isMethodHooked($sanitizer, $method)) {
			return $method;
		}
		return 'text';
	}

	/**
	 * Sanitize Value by Sanitizer Method
	 * @param  string $subject
	 * @param  string $method
	 * @return mixed
	 */
	protected static function sanitizeByMethod($subject, $method) {
		/** @var Sanitizer */
		$sanitizer = self::pw('sanitizer');
		// Sanitize Data
		// If no sanitizer is defined, use the text sanitizer as default
		$method = $method ? $method : 'text';
		$method = self::parseSanitizerMethod($method);
		return $sanitizer->$method($subject);
	}

	// public static function getPagenbrFromOffset(int $offset, int $showOnPage = null) {
	// 	return PurlPaginator::getPagenbrFromOffset($offset, $showOnPage);
	// }

/* =============================================================
	ProcessWire Components
============================================================= */
	/**
	 * Return ProcessWire Config
	 * @return PwConfig
	 */
	protected static function getPwConfig() {
		return self::pw('config');
	}

	/**
	 * Return ProcessWire Session
	 * @return Input
	 */
	protected static function getPwInput() {
		return self::pw('input');
	}

	/**
	 * Return ProcessWire Session
	 * @return Page
	 */
	protected static function getPwPage() {
		return self::pw('page');
	}

	/**
	 * Return ProcessWire Pages
	 * @return Pages
	 */
	protected static function getPwPages() {
		return self::pw('pages');
	}

	/**
	 * Return ProcessWire Current User
	 * @return User
	 */
	protected static function getPwUser() {
		return self::pw('user');
	}

	/**
	 * Return ProcessWire Session
	 * @return Roles
	 */
	protected static function getPwRoles() {
		return self::pw('roles');
	}

	/**
	 * Return ProcessWire Sanitizer
	 * @return Sanitizer
	 */
	protected static function getPwSanitizer() {
		return self::pw('sanitizer');
	}

	/**
	 * Return ProcessWire Session
	 * @return Session
	 */
	protected static function getPwSession() {
		return self::pw('session');
	}

	/**
	 * Return ProcessWire Session
	 * @return Users
	 */
	protected static function getPwUsers() {
		return self::pw('users');
	}
}
