<?php namespace App\Util;
// ProcessWire
use ProcessWire\Config;
// Pauldro ProcessWire
use Pauldro\ProcessWire\AbstractStaticPwClass;

class InitConfig extends AbstractStaticPwClass {
    const CONFIGS = [
        'useUomPriceByWeight' => false,
        'decimalPrecisionQty'    => 0
    ];

    public static function init() {
        $config = self::pwConfig();

        foreach (self::CONFIGS as $key => $value) {
            if ($config->has($key)) {
                continue;
            }
            $config->set($key, $value);
        }
    }

    private static function pwConfig() : Config {
        return self::pw('config');
    }
}