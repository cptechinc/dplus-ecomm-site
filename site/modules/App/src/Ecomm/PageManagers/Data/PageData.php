<?php namespace App\Ecomm\PageManagers\Data;
// ProcessWire
use ProcessWire\WireData;

/**
 * Container for Page Data
 * 
 * @property int    $id
 * @property string $title
 * @property string $name
 * @property string $dplusid
 * @property string $dplusdescription
 */
class PageData extends WireData {
    public function __construct() {
        $this->id = 0;
        $this->title            = '';
        $this->name             = '';
        $this->dplusid          = '';
        $this->dplusdescription = '';
    }
}