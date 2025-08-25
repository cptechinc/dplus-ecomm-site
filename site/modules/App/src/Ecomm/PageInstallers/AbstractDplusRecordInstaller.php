<?php namespace App\Ecomm\PageInstallers;
// Propel ORM Library
use Propel\Runtime\ActiveRecord\ActiveRecordInterface as AbstractRecord;
use Propel\Runtime\Util\PropelModelPager;
// Pauldro\ProcessWire
use Pauldro\ProcessWire\Installers\AbstractStaticPwInstaller;
// Dplus
use Dplus\Abstracts\AbstractFilterData;
use Dplus\Abstracts\AbstractQueryWrapper;

abstract class AbstractDplusRecordInstaller extends AbstractStaticPwInstaller {
    const RECORDSPERLOOP = 100;
	
	public static $results;

    public static function install() : void {
        $filter = static::generateFilterData();
		$filter->limit = static::RECORDSPERLOOP;
        $nbrOfPages   = ceil(static::countSrcRecords() / static::RECORDSPERLOOP);
        $TABLE = static::srcTable();
        $results = [];

        for ($i = 1; $i <= $nbrOfPages; $i++) {
			$filter->pagenbr = $i;
			$list = $TABLE->findPaginatedByFilterData($filter);
			$listResults = static::installPropelModelPagerList($list);
			$results = array_merge($results, $listResults);
		}
        static::$results = $results;
    }

	public static function uninstall() : void {

	}

/* =============================================================
	Install Page
============================================================= */
    /**
	 * Install Pages
	 * @param  PropelModelPager[AbstractRecord] $list
	 * @return array
	 */
	public static function installPropelModelPagerList(PropelModelPager $list) : array {
		$results = [];
		
		foreach ($list as $item) {
			$results[$item->id] = static::installOneRecord($item);
		}
		return $results;
	}

	public static function installOneRecord(AbstractRecord $r) : bool {
		return static::pageCreateOrUpdate($r);
	}

/* =============================================================
	Data Fetching
============================================================= */
	protected static function countSrcRecords() : int {
        return static::srcTable()->countAll();
    }

    abstract protected static function pageExists(AbstractRecord $r) : bool;

/* =============================================================
	Record Page CRUD
============================================================= */
    protected static function pageCreateOrUpdate(AbstractRecord $r) : bool {
        if (static::pageExists($r) === false) {
            return static::pageCreate($r);
        }
        return static::pageUpdate($r);
    }

    abstract protected static function pageCreate(AbstractRecord $r) : bool;

    abstract protected static function pageUpdate(AbstractRecord $r) : bool;

	abstract protected static function pageDelete(AbstractRecord $r) : bool;

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * @return AbstractQueryWrapper
	 */
	abstract protected static function srcTable();

	/**
	 * @return AbstractFilterData
	 */
	abstract protected static function generateFilterData();
}