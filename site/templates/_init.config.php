<?php 
use App\Configs\Init as InitConfigs;

InitConfigs\App::instance()->init();
InitConfigs\Account::instance()->init();
InitConfigs\Checkout::instance()->init();
InitConfigs\Dpay::instance()->init();
InitConfigs\Register::instance()->init();
InitConfigs\Site::instance()->init();

App\Util\InitConfig::init();

if ($config->useUomPriceByWeight === true) {
	$config->decimalPrecisionQty = Dplus\Database\Tables\Configs\So::config()->decimal_places_qty;
}

// Load Company Config Values
if (file_exists("./init/config/$config->companyid.php")) {
	include("./init/config/$config->companyid.php");
}