<?php namespace Controllers\Admin\Rebuild\Pages;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\CodeTables\ItemGroup as ItemGroupTable;
// App
use App\Ecomm\PageInstallers\Products\ItemGroups\Installer;
use App\Util\Data\JsonResultData as ResultData;
// Controllers
use Controllers\Abstracts\AbstractJsonController;


/**
 * ProductsItemGroups
 * Handles Products Item Groups Rebuild Page
 */
class ProductsItemGroups extends AbstractJsonController {
	const SESSION_NS = 'admin-rebuild-pages-products-item-groups';
	const TEMPLATE   = 'admin-site-rebuild';
	const PWROLES    = ['superuser', 'site-admin'];

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		self::sanitizeParametersShort($data,  ['id|string']);

		if (self::validatePwRole() === false) {
			$result = new ResultData();
			$result->error = true;
			$result->msg = "User is not allowed";
			return $result->data;
		}
	
		if ($data->id != '') {
			return self::rebuildOne($data);
		}
		return self::rebuildAll($data);
	}

	private static function rebuildOne(WireData $data) {
		$item = ItemGroupTable::instance()->fetch($data->id);
		$result = new ResultData();

		if (empty($item)) {
			$result->error = true;
			$result->msg = "Item Group '$data->id' not found";
			return $result->data;
		}
		$result->success = Installer::installOneFromRecord($item);
		$result->error = $result->success === false;
		$result->msg = $result->success ? "Item Page '$data->id' installed" : "Item Page '$data->id' not installed";
		return $result->data;
	}

	private static function rebuildAll(WireData $data) {
		$result = new ResultData();
		$result->success = boolval(Installer::install());
		$result->error = $result->success === false;
		$result->msg = "Installed " . sizeof(Installer::$results) . " Item Group Pages";
		return $result->data;
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */

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