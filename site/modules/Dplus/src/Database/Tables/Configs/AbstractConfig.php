<?php namespace Dplus\Database\Tables\Configs;
// Propel ORM Library
use Propel\Runtime\ActiveQuery\ModelCriteria as Query;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as Model;
// Dplus
use Dplus\Abstracts\AbstractQueryWrapper;

/**
 * AbstractConfig
 * Template for loading Config
 * 
 * @method Query query()
 * @static self  $instance
 */
abstract class AbstractConfig extends AbstractQueryWrapper {
	const MODEL = '';
	const MODEL_TABLE  = '';
	const YN_TRUE = 'Y';

	/** @var Model */
	protected static $config;


	/**
	 * Return Config from Database
	 * @return Model
	 */
	public static function fetchConfig() {
		return static::instance()->query()->findOne();
	}

	/**
	 * Return Config from Memory
	 * @return Model
	 */
	public static function config() {
		if (static::$config) {
			return static::$config;
		}
		static::$config = static::fetchConfig();
		return static::$config;
	}
}