<?php namespace Controllers\Abstracts;
// Base PHP
use ReflectionClass;
// Mvc Controllers
use Mvc\Controllers\AbstractController as ParentController;

/**
 * AbstractController
 */
abstract class AbstractController extends ParentController {
	const SESSION_NS = '';

/* =============================================================
	1. Indexes
============================================================= */

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */

/* =============================================================
	4. URLs
============================================================= */

/* =============================================================
	5. Displays
============================================================= */

/* =============================================================
	6. HTML Rendering
============================================================= */

/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	8. Supplemental
============================================================= */
	/**
	 * Return Reflection Class
	 * @return ReflectionClass
	 */
	protected static function getStaticReflectionClass() {
		return new ReflectionClass(static::class);
	}

	/**
	 * Return Namespace as path
	 * @return string
	 */
	protected static function getNamespaceAsPath() {
		$insp = static::getStaticReflectionClass();
		$ns = $insp->getNamespaceName();
		$ns = preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $ns);
		$ns = strtolower($ns);
		$ns = ltrim($ns, 'controllers\\');
		$ns = str_replace("\\", '/', $ns);
		return $ns;
	}

	/**
	 * Return Class Name as path
	 * @return string
	 */
	protected static function getClassNameAsPath() {
		$insp = static::getStaticReflectionClass();
		$class = $insp->getShortName();
		$class = preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $class);
		$class = strtolower($class);
		return $class;
	}

	/**
	 * Return Namespace + Class Name as Path
	 * @return string
	 */
	protected static function getNamespaceClassNameAsPath() {
		return ltrim(static::getNamespaceAsPath() . '/' . static::getClassNameAsPath(), '/');
	}

/* =============================================================
	9. Hooks / Object Decorating
============================================================= */

/* =============================================================
	10. Sessions
============================================================= */
	/**
	 * Set Session Variable
	 * @param  string $key
	 * @param  string $value
	 * @return bool
	 */
	public static function setSessionVar($key = '', $value) {
		return self::pw('session')->setFor(static::SESSION_NS, $key, $value);
	}

	/**
	 * Return Session Variable
	 * @param  string $key
	 * @return mixed
	 */
	public static function getSessionVar($key = '') {
		return self::pw('session')->getFor(static::SESSION_NS, $key);
	}

	/**
	 * Delete Session Variable
	 * @param  string $key
	 * @return bool
	 */
	public static function deleteSessionVar($key = '') {
		return self::pw('session')->removeFor(static::SESSION_NS, $key);
	}
}
