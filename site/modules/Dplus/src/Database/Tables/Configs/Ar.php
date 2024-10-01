<?php namespace Dplus\Database\Tables\Configs;
// Dplus Models
use ConfigArQuery as Query, ConfigAr;
// Propel ORM Library
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as Model;

/**
 * AbstractConfig
 * Template for loading Config
 * 
 * @method Query query()
 * @static self  $instance
 * @static ConfigAr config()  Return Config
 */
class Ar extends AbstractConfig {
	const MODEL = 'ConfigAr';
	const MODEL_TABLE  = 'ar_config';
	const YN_TRUE = 'Y';

	/** @var Model */
	protected static $config;
}