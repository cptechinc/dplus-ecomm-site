<?php namespace Controllers\Admin\Rebuild\Pages;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\Item as ItemTable;
use Dplus\Database\Tables\CodeTables\ItemGroup as ItemGroupTable;
// App
use App\Ecomm\PageInstallers\Products\AbstractInstaller as Installer;
use App\Util\Data\JsonResultData as ResultData;
// Controllers
use Controllers\Abstracts\AbstractJsonController;


/**
 * Products
 * Handles Products Rebuild Page
 */
class Products extends AbstractJsonController {
	const SESSION_NS = 'admin-rebuild-pages-products';
	const TEMPLATE = 'admin-site-rebuild';
	const PWROLES  = ['superuser', 'site-admin'];

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		self::sanitizeParametersShort($data,  ['itemID|string', 'itemgroup|string']);

		if (self::validatePwRole() === false) {
			$result = new ResultData();
			$result->error = true;
			$result->msg = "User is not allowed";
			return $result->data;
		}
	
		if ($data->itemID != '') {
			return self::rebuildOne($data);
		}
		if ($data->itemgroup != '') {
			return self::rebuildByItemGroup($data);
		}
		return self::rebuildAll($data);
	}

	private static function rebuildOne(WireData $data) {
		$item = ItemTable::instance()->item($data->itemID);
		$result = new ResultData();

		if (empty($item)) {
			$result->error = true;
			$result->msg = "Item '$data->itemID' not found";
			return $result->data;
		}
		$result->success = Installer::installOneFromRecord($item);
		$result->error = $result->success === false;
		$result->msg = $result->success ? "Item Page '$data->itemID' installed" : "Item Page '$data->itemID' not installed";
		return $result->data;
	}

	private static function rebuildAll(WireData $data) {
		$result = new ResultData();
		$result->success = boolval(Installer::install());
		$result->error = $result->success === false;
		$result->msg = "Installed " . sizeof(Installer::$results) . " Item Pages";
		return $result->data;
	}

	private static function rebuildByItemGroup(WireData $data) {
		$result = new ResultData();

		if (ItemGroupTable::instance()->exists($data->itemgroup) === false) {
			$result->error = true;
			$result->msg = "Item Group '$data->itemgroup' not found";
			return $result->data;
		}
		$ITEMS = ItemTable::instance();
		$items = $ITEMS->findByItemids($ITEMS->itemidsByInvGroup($data->itemgroup));
		$result->success = boolval(Installer::installPropelObjectCollection($items));
		$result->error = $result->success === false;
		$result->msg = "Installed " . sizeof(Installer::$results) . " Item Pages";
		return $result->data;
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
	/**
	 * Return ItemIDs that are associated with ItemGroup
	 * @param  string $id
	 * @return array
	 */
	private static function findItemidsByItemGroup($id) {
		return ItemTable::instance()->itemidsByInvGroup($id);
	}


/* =============================================================
	4. URLs
============================================================= */
	

/* =============================================================
	5. Displays
============================================================= */
	

/* =============================================================
	6. HTML Rendering
============================================================= */

/* =============================================================
	7. Class / Module Getters
============================================================= */

/* =============================================================
	8. Supplemental
============================================================= */

/* =============================================================
	9. Hooks / Object Decorating
============================================================= */
}