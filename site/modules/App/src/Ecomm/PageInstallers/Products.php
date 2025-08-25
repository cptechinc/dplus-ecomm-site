<?php namespace App\Ecomm\PageInstallers;
// Propel ORM Library
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as AbstractRecord;
// Dplus Models
use ItemMasterItem as Record;
// Dplus
use Dplus\Database\Tables\Item\FilterData;
use Dplus\Database\Tables\Item as ItemsTable;
// App
use App\Ecomm\PageManagers\ProductsManager as Repository;
use App\Ecomm\PageManagers\Data\PageData;


class Products extends AbstractDplusRecordInstaller {
    public static $results;
    
    public static function uninstall() : void {
        $results = Repository::deleteAll();
		static::$results = $results;
	}

/* =============================================================
    Data Fetching
============================================================= */
    /**
     * @param  Record $r
     * @return bool
     */
    protected static function pageExists(AbstractRecord $r) : bool {
        return Repository::exists($r->id);
    }

/* =============================================================
    Record Page CRUD
============================================================= */
    /**
     * @param  Record $r
     * @return bool
     */
    protected static function pageCreate(AbstractRecord $r) : bool {
        $data = new PageData();
        $data->dplusid          = $r->itemid;
        $data->dplusdescription = $r->description;
        $data->title            = $r->description;
        $data->name             = $r->itemid;
        return Repository::create($data);
    }

    /**
     * @param  Record $r
     * @return bool
     */
    protected static function pageUpdate(AbstractRecord $r) : bool {
        $data = new PageData();
        $data->dplusid          = $r->itemid;
        $data->dplusdescription = $r->description;
        $data->title            = $r->description;
        $data->name             = $r->itemid;
        return Repository::update($data->dplusid, $data);
    }

    /**
     * @param  Record $r
     * @return bool
     */
    protected static function pageDelete(AbstractRecord $r) : bool {
        if (Repository::exists($r->id) === false) {
            return true;
        }
        return Repository::delete($r->id);
    }

/* =============================================================
	Supplemental
============================================================= */
	protected static function srcTable() : ItemsTable {
		return ItemsTable::instance();
	}

	protected static function generateFilterData() : FilterData {
		return new FilterData();
	}
}