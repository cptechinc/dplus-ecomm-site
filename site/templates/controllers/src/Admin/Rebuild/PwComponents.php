<?php namespace Controllers\Admin\Rebuild;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Ecomm\Pages\TemplatesInstaller;
use App\Ecomm\Pages\Pages;
// Controllers
use Controllers\Abstracts\AbstractController;


/**
 * PwComponents
 * Handles PwComponents Rebuild Page
 */
class PwComponents extends AbstractController {
	const SESSION_NS = 'admin-rebuild-templates-pages';
	const TEMPLATE = 'admin-site-rebuild';
	const PWROLES  = ['superuser', 'site-admin'];

/* =============================================================
	1. Indexes
============================================================= */
	public static function index(WireData $data) {
		$fields = ['action|text', 'logout|bool'];
		self::sanitizeParametersShort($data, $fields);

		if (self::validatePwRole() === false) {
			return false;
		}
	
		$results = [
			'templates' => self::rebuildTemplates(),
			'pages' => ['site' => []]
		];
		Pages\Site::install();
		$results['pages']['site'] = Pages\Site::$results;
		return $results;
	}

/* =============================================================
	2. Validations / Permissions / Initializations
============================================================= */
	protected static function rebuildTemplates()  {
		$templates = new TemplatesInstaller();
		$templates->installOnlyNew = false;
		$results = ['installed' => []];
		$templates->install();
		$results['installed'] = $templates->installed;
		return $results;
	}

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */

/* =============================================================
	4. URLs
============================================================= */
	

/* =============================================================
	5. Displays
============================================================= */
	private static function display(WireData $data) {
		return self::render($data);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function render(WireData $data) {
		return self::getTwig()->render('admin/page.twig');
	}

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