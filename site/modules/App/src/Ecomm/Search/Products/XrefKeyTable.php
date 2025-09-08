<?php namespace App\Ecomm\Search\Products;
// Dplus Models
use ItemXrefKeyQuery as Query;
// Dplus
use Dplus\Search\XrefKeyTable as ParentTable;

class XrefKeyTable extends ParentTable {
    protected static $instance;

/* =============================================================
	Query Functions
============================================================= */
    /**
     * Return Query Filtered By sources
     * @return Query
     */
    public function querySources() {
       return $this->query()->filterByRkeysource([0,6]);
    }
}