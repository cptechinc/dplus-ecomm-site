<?php namespace App\Util;
// Hashids Library
use Hashids\Hashids as Hasher;
// ProcessWire
use ProcessWire\WireData;

/**
 * Hashids
 * Wrapper for Hashids\Hashids to Hash Page IDs
 * 
 * @property Hasher $hashids Hashids Library
 * @property string $salt    Salt to use for Hashing
 */
class Hashids extends WireData {
	const ALPHABET = 'abcdefghijklmnopqrstuvwxyz1234567890';
	const SALT     = 'cptech';

	protected $hashids;
	protected $salt;

	private static $instance;

	/**
	 * Return Instance
	 * @return static
	 */
	public static function instance() {
		if (empty(self::$instance)) {
			$instance = new self();
			self::$instance = $instance;
		}
		return self::$instance;
	}

	public function __construct() {
		$this->salt = $this->wire('config')->saltHashids;
		if (empty($this->salt)) {
			$this->salt = self::SALT;
		}
		$this->hashids = new Hasher($this->salt, 0, self::ALPHABET);
	}

	/**
	 * Return Hash for ID
	 * @param  mixed $id
	 * @return string
	 */
	public function encode($id) {
		return $this->hashids->encode($id);
	}

	/**
	 * Return Decoded ID from Hash
	 * @param  string $hash Hash
	 * @return string
	 */
	public function decode($hash) {
		return $this->hashids->decode($hash);
	}
}
