<?php namespace Controllers\Admin\Rebuild\Pages;
// ProcessWire
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\Item as ItemTable;
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
		self::sanitizeParametersShort($data,  ['itemID|string']);

		if (self::validatePwRole() === false) {
			$result = new ResultData();
			$result->error = true;
			$result->msg = "User is not allowed";
			return $result->data;
		}
	
		if ($data->itemID != '') {
			return self::rebuildOne($data);
		}
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