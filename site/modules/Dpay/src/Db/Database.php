<?php namespace Dpay\Db;
// Base PHP
use PDO, PDOException;
// Meekro DB
use MeekroDB;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireDatabasePDO;


/**
 * Database
 * Connector for Dpay Database
 */
class Database extends WireData {
	/**
	 * PDO Connector
	 * @var PDO
	 */
	private static $pdo = false;

	/**
	 * MeekroDB connector
	 * @var MeekroDB
	 */
	private static $meekrodb = false;

	/**
	 * ProcessWire PDO connector
	 * @var WireDatabasePDO
	 */
	private static $pwdb = false;


/* =============================================================
	Constructors / Inits
============================================================= */

/* =============================================================
	Public 
============================================================= */
	/**
	 * Return if db is able to connect
	 * @return bool
	 */
	public static function connect() {
		if (self::connectPdo() === false) {
			return false;
		}
		self::connectMeekroDb();
		self::connectPwDb();
		return true;
	}

	/**
	 * Return PDO Connection
	 * @return PDO
	 */
	public static function pdo() {
		if (empty(self::$pdo)) {
			return false;
		}
		return self::$pdo;
	}

	/**
	 * Return Meekro DB Connection
	 * @return MeekroDB
	 */
	public static function meekdrodb() {
		if (empty(self::$meekrodb)) {
			return false;
		}
		return self::$meekrodb;
	}

	/**
	 * Return ProcessWire PDOConnection
	 * @return WireDatabasePDO 
	 */
	public static function pwdb() {
		if (empty(self::$pwdb)) {
			return false;
		}
		return self::$pwdb;
	}

/* =============================================================
	Internal Processing
============================================================= */	
	/**
	 * Return if PDO connection was able to be made
	 * @return bool
	 */
	private static function connectPdo() {
		if (empty(self::$pdo) === false) {
			return true;
		}

		try {
			$db = self::readEnvDbCreds();
			$pdo = new PDO(self::generateDsnFromDbCreds($db), $db->user, $db->password);
		} catch(PDOException $e) {
			return false;
		}
		self::$pdo = $pdo;
		return true;
	}

	/**
	 * Initialize Meekro DB
	 * @return bool
	 */
	private static function connectMeekroDb() {
		$db = self::readEnvDbCreds();
		$meekrodb = new MeekroDB(self::generateDsnFromDbCreds($db), $db->user, $db->password);
		self::$meekrodb = $meekrodb;
	}

	/**
	 * Initialize Meekro DB
	 * @return bool
	 */
	private static function connectPwDb() {
		$db = self::readEnvDbCreds();
		try {
			$pwdb = new WireDatabasePDO(self::generateDsnFromDbCreds($db), $db->user, $db->password);
		} catch(\Exception $e) {
			return false;
		}
		self::$pwdb = $pwdb;
	}
	
	/**
	 * Return Database Credentials from ENV
	 * @return WireData
	 */
	private static function readEnvDbCreds() {
		$db = new WireData();
		$db->name = $_ENV['DPAY.DB_NAME'];
		$db->host = $_ENV['DPAY.DB_HOST'];
		$db->port = $_ENV['DPAY.DB_PORT'];
		$db->user = $_ENV['DPAY.DB_USER'];
		$db->password = $_ENV['DPAY.DB_PASS'];
		return $db;
	}

	/**
	 * Return DSN from DB Creds
	 * @param  WireData $db
	 * @return string
	 */
	private static function generateDsnFromDbCreds(WireData $db) {
		return "mysql:host=$db->host;port=$db->port;dbname=$db->name";
	}
}