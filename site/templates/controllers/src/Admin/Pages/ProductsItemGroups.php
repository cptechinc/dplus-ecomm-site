<?php namespace Controllers\Admin\Pages;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\Pages;
use ProcessWire\PageArray;
use ProcessWire\Wire404Exception;
use ProcessWire\WireData;
// Controllers
use Controllers\Abstracts\AbstractController;
use Controllers\Admin as AdminController;
// App
use App\Ecomm\PageInstallers\ProductItemGroups as PagesInstaller;
use App\Ecomm\PageManagers\ProductItemGroupsManager as PageRepo;
use App\Ecomm\Pages\Templates\ProductsItemGroup as Template;

class ProductsItemGroups extends AbstractController {
	const SESSION_NS = 'admin-products-item-groups';
	const TEMPLATE = 'admin-site';
    const ALLOWED_PWROLES  = ['superuser', 'site-admin'];
    const LIMIT_ON_PAGE = 25;

    public static function index(WireData $data) {
		if (self::validatePwRole() === false) {
			throw new Wire404Exception("Not Logged In");
		}

		if ($data->has('action')) {
			return self::process($data);
		}

		self::initPageHooks();
        self::pw('page')->title = "Product Item Group Pages";
        $list = static::fetchList($data);
        $html = self::displayList($data, $list);
		self::deleteSessionVar('last-deleted');
		return $html;
	}

	public static function process(WireData $data) {
		if (self::validatePwRole() === false) {
			self::getPwSession()->redirect(self::url(), $http301=false);
		}
		$fields = ['action|text', 'dplusid|string'];
		self::sanitizeParametersShort($data, $fields);

		switch ($data->action) {
			case 'delete':
				$success = PageRepo::delete($data->dplusid);
				if ($success) {
					self::setSessionVar('last-deleted', [$data->dplusid]);
				}
				self::getPwSession()->redirect(self::url(), $http301=false);
				break;
			case 'delete-all':
				PagesInstaller::uninstall();
				$ids = PagesInstaller::$results;
				if ($ids) {
					self::setSessionVar('last-deleted', $ids);
				}
				self::getPwSession()->redirect(self::url(), $http301=false);
				break;
			case 'install-all':
				PagesInstaller::install();
				self::getPwSession()->redirect(self::url(), $http301=false);
				break;
		}
	}

/* =============================================================
	4. URLs
============================================================= */
    public static function url() {
        return Menu::url() . 'products-item-groups/';
    }

    public static function deleteUrl($dplusid) {
        return self::url() . '?' . http_build_query(['action' => 'delete', 'dplusid' => $dplusid]);
    }

    public static function deleteAllUrl() {
        return self::url() . '?action=delete-all';
    }

    public static function installAllUrl() {
        return self::url() . '?action=install-all';
    }

/* =============================================================
	5. Displays
============================================================= */
	private static function displayList(WireData $data, PageArray $list) {
		return self::renderList($data, $list);
	}

/* =============================================================
	6. HTML Rendering
============================================================= */
	private static function renderList(WireData $data, PageArray $list) {
		return self::getTwig()->render('admin/pages/products-item-groups/page.twig', ['list' => $list]);
	}

/* =============================================================
	3. Data Fetching / Requests / Retrieval
============================================================= */
    protected static function fetchList(WireData $data) {
        /**  @var Pages */
        $pages = self::pw('pages');

        $data->template = Template::NAME;
        $data->pageNum = self::pw('input')->pageNum;
        $data->limit   = static::LIMIT_ON_PAGE;
        $data->offset  = $data->pageNum > 1 ? ($data->pageNum * $data->limit) - $data->limit : 0;

        return $pages->find("template=$data->template,limit=$data->limit,start=$data->offset");
    }

/* =============================================================
	9. Hooks / Object Decorating
============================================================= */
	/**
	 * Initialze Page Hooks
	 * @param  string $tplname
	 * @return void
	 */
	public static function initPageHooks($tplname = '') {
		$selector = static::getPageHooksTemplateSelector();
		$m = self::pw('modules')->get('App');

		$m->addHook("$selector::deleteUrl()", function(HookEvent $event) {
			$event->return = self::deleteUrl($event->arguments(0));
		});

		$m->addHook("$selector::deleteAllUrl()", function(HookEvent $event) {
			$event->return = self::deleteAllUrl();
		});

		$m->addHook("$selector::installAllUrl()", function(HookEvent $event) {
			$event->return = self::installAllUrl();
		});

		$m->addHook("$selector::menuUrl()", function(HookEvent $event) {
			$event->return = Menu::url();
		});
	}
}