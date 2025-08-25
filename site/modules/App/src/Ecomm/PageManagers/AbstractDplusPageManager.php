<?php namespace App\Ecomm\PageManagers;
// ProcessWire
use ProcessWire\Page;
use ProcessWire\Pages;
use ProcessWire\Sanitizer;
// Pauldro ProcessWire
use Pauldro\ProcessWire\AbstractStaticPwClass;
// App
use App\Ecomm\PageManagers\Data\PageData;

abstract class AbstractDplusPageManager extends AbstractStaticPwClass {
	const TEMPLATE = '';
	const PARENT_TEMPLATE = '';
	const DPLUSID_FIELDNAME = 'dplusid';

	protected static function pageSelector(string $id) : string {
		return sprintf("template=%s,%s=%s", static::TEMPLATE, static::DPLUSID_FIELDNAME, $id);
	}
	
/* =============================================================
	CRUD Creates / Updates
============================================================= */
	protected static function newPage() : Page {
		$p = new Page();
		$p->template = static::TEMPLATE;
		$p->parent   = self::pwPages()->get('template=' . static::PARENT_TEMPLATE);
		return $p;
	}

	public static function create(PageData $data) : bool {
		$p = static::newPage();
		static::setPageData($p, $data);
		return $p->save();
	}

	public static function update($id, PageData $data) : bool {
		if (static::exists($id) === false) {
			return false;
		}
		$p = self::page($id);
		if ($p->template->name != static::TEMPLATE) {
			return false;
		}
		return static::updatePage($p, $data);
	}

	protected static function createOrUpdate(PageData $data) : bool {
		if ($data->id == 0 || static::exists($data->id) === false) {
			return static::create($data);
		}
		return static::update($data->id, $data);
	}

	protected static function updatePage(Page $p, PageData $data) : bool {
		$p->of(false);
		static::setPageData($p, $data);
		return $p->save();
	}

	protected static function setPageData(Page $p, PageData $data) : void {
		$p->dplusid          = $data->dplusid;
		$p->dplusdescription = $data->dplusdescription;
		$p->title            = $data->title;
		$p->name             = self::pwSanitizer()->pageName($data->name);
	}

/* =============================================================
	CRUD Reads
============================================================= */
	public static function exists($id) : bool {
		return self::pwPages()->count(static::pageSelector($id)) > 0;
	}

	public static function page($id) : Page {
		return self::pwPages()->get(static::pageSelector($id));
	}

/* =============================================================
	CRUD Deletes
============================================================= */
	public static function delete($id) : bool {
		if (static::exists($id) === false) {
			return true;
		}
		$p = static::page($id);
		return $p->delete();
	}

	public static function deleteAll() : array {
		$results = [];
		$pages = self::pwPages()->find('template=' . static::TEMPLATE);

		foreach ($pages as $page) {
			/** @var Page $page */
			$page->of(true);
			$results[$page->get(static::DPLUSID_FIELDNAME)] = $page->delete();
		}
		return $results;
	}


/* =============================================================
	ProcessWire Pages
============================================================= */
	protected static function pwPages() : Pages {
		return self::pw('pages');
	}

	protected static function pwSanitizer() : Sanitizer {
		return self::pw('sanitizer');
	}
}