<?php namespace Dplus\Database\Tables\Configs;
// Dplus Models
use ConfigSalesOrderQuery as Query, ConfigSalesOrder;
// Propel ORM Library
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as Model;

/**
 * AbstractConfig
 * Template for loading Config
 * 
 * @method Query query()
 * @static self  $instance
 * @static ConfigSo config()  Return Config
 */
class So extends AbstractConfig {
	const MODEL = 'ConfigSalesOrder';
	const MODEL_TABLE  = 'SO_config';
	const YN_TRUE = 'Y';

	/** @var Model */
	protected static $config;
}