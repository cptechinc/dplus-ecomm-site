<?php namespace Controllers\Account\Invoices;
// ProcessWire
use ProcessWire\WireData;
// Controllers
use Controllers\Abstracts\AbstractController as AbstractParentController;

/**
 * AbstractController
 * Template for handling the Invoices page
 */
abstract class AbstractController extends AbstractParentController {
	const SESSION_NS = 'invoices';
	const REQUIRE_LOGIN = true;
	const TEMPLATE      = 'account';
	const TITLE         = 'Invoices';
	const PAGE_NAME     = 'invoices';

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */
	/**
	 * Init
	 * @return bool
	 */
	protected static function init(WireData $data = null) {
		if (static::initLogin($data) === false) {
			return false;
		}
		return true;
	}


/* =============================================================
	4. URLs
============================================================= */
	/**
	 * Return URL to List Page
	 * @return string
	 */
	protected static function url() {
		return self::pw('pages')->get('template=account')->url . 'invoices/';
	}
}