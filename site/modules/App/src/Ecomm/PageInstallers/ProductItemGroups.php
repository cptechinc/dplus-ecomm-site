<?php namespace App\Ecomm\PageInstallers;
// Propel ORM Library
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as AbstractRecord;
// Dplus Models
use InvGroupCode as Record;
// Dplus
use Dplus\Database\Tables\CodeTables\FilterData;
use Dplus\Database\Tables\CodeTables\ItemGroup as ItemGroupTable;
// App
use App\Ecomm\PageManagers\ProductItemGroupsManager as Repository;
use App\Ecomm\PageManagers\Data\PageData;


class ProductItemGroups extends AbstractDplusRecordInstaller {
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
        $data->dplusid          = $r->code;
        $data->dplusdescription = $r->description;
        $data->title            = $r->description;
        $data->name             = $r->code;
        return Repository::create($data);
    }

    /**
     * @param  Record $r
     * @return bool
     */
    protected static function pageUpdate(AbstractRecord $r) : bool {
        $data = new PageData();
        $data->dplusid          = $r->code;
        $data->dplusdescription = $r->description;
        $data->title            = $r->description;
        $data->name             = $r->code;
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
	protected static function srcTable() : ItemGroupTable {
		return ItemGroupTable::instance();
	}

	protected static function generateFilterData() : FilterData {
		return new FilterData();
	}
}