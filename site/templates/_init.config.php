<?php 
use App\Configs\Init as InitConfigs;

InitConfigs\App::instance()->init();
InitConfigs\Register::instance()->init();
InitConfigs\Site::instance()->init();

// Load Company Config Values
if (file_exists("./init/config/$config->companyid.php")) {
	include("./init/config/$config->companyid.php");
}