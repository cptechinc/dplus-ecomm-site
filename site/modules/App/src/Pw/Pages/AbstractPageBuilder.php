<?php namespace App\Pages;
// ProcessWire
use ProcessWire\Page;
use ProcessWire\Pages;
use ProcessWire\WireData;
use ProcessWire\ProcessWire;
// App
use App\Pw\AbstractStaticPwInstaller;

/**
 * AbstractPageBuilder
 * Template for Creating / Updating ProcessWire Pages
 */
abstract class AbstractPageBuilder extends AbstractStaticPwInstaller {
	const PAGE_FIELDS = [
		'name'     => '',
		'oldName'  => '',
		'title'    => '',
		'headline' => '',
		'summary'  => '',
		'template' => '',
	];

	const PAGE_FIELDS_EXTRA = [];

	const PAGES = [
		// 'mgl' => [
		// 	'name'     => 'mgl',
		// 	'title'    => 'General Ledger',
		// 	'summary'  => 'General Ledger Menu',
		// 	'permissioncode' => 'mgl',
		// 	'menucode' => 'mgl',
		// 	'template' => 'dplus-menu',
		// 	'parentSelector'   => '/',
		// ]
	];


/* =============================================================
	Page fields
============================================================= */
	/**
	 * Return Page fields with default values
	 * @return array
	 */
	protected static function getPageFields() {
		return array_merge(static::PAGE_FIELDS, static::PAGE_FIELDS_EXTRA);
	}

	/**
	 * Return List of Fields that are simple for setting
	 * @return array[string]
	 */
	protected static function getPageSimpleFields() {
		return ['title', 'headline', 'summary', 'template'];
	}

/* =============================================================
	CRUD Reads
============================================================= */
	/**
	 * Return If Page exists by Template, Name
	 * @param  string $template
	 * @param  string $name
	 * @return bool
	 */
	public static function existsByTemplateAndName($template, $name) {
		$pages = self::getPwPages();
		return boolval($pages->count("template=$template,name=$name"));
	}

	/**
	 * Return Page by Template, Name
	 * @param  string $template
	 * @param  string $name
	 * @return Page
	 */
	public static function getByTemplateAndName($template, $name) {
		return self::getPwPages()->get("template=$template,name=$name");
	}

/* =============================================================
	CRUD Creates
============================================================= */	
	/**
	 * Create Page
	 * @param  WireData $data
	 * @return bool
	 */
	protected function create(WireData $data) {
		$parent = self::getPwPages()->get($data->parentSelector);
		if ($parent->id == 0) {
			return false;
		}
		$p = new Page();
		$p->template = $data->template;
		$p->name     = $data->name;
		$p->title    = $data->title;
		$p->parent   = $parent;
		$saved = $p->save();
		if ($saved === false) {
			return false;
		}
		return $this->update($p, $data);
	}

/* =============================================================
	CRUD Updates
============================================================= */
	/**
	 * Update Existing Page
	 * @param  Page     $p
	 * @param  WireData $data
	 * @return bool
	 */
	protected function update(Page $p, WireData $data) {
		$p->of(false);
		$this->setSimpleFields($p, $data);
		$this->setExtraFields($p, $data);

		if ($data->oldParentSelector != '' && $data->oldParentSelector != $data->parentSelector) {
			$parent = self::getPwPages()->get($data->parentSelector);

			if ($parent->id == 0) {
				return false;
			}
			$p->parent = $parent;
		}

		if ($data->oldName != '' && $data->oldName != $data->name) {
			$p->name = $data->name;
		}
		return $p->save();
	}

	/**
	 * Update Simple Field Values
	 * @param  Page     $p
	 * @param  WireData $data
	 * @return void
	 */
	protected function setSimpleFields(Page $p, WireData $data) {
		foreach ($this->getPageSimpleFields() as $fieldName) {
			$p->$fieldName = $data->$fieldName;
		}
	}

	/**
	 * Update Extra Field Values
	 * @param  Page     $p
	 * @param  WireData $data
	 * @return void
	 */
	protected function setExtraFields(Page $p, WireData $data) {
		
	}

/* =============================================================
	CRUD Processing
============================================================= */
	/**
	 * Build Pages
	 * @return bool
	 */
	protected static function _install() {
		foreach (static::PAGES as $page) {
			static::installOne($page);
		}
		return false;
	}

	/**
	 * Create / Update Page
	 * @param  array $pageAttr
	 * @return bool
	 */
	protected static function installOne(array $pageAttr) {
		$data = new WireData();
		$data->setArray(array_merge(static::getPageFields(), $pageAttr));
		
		$page = static::getByTemplateAndName($data->template, $data->name);

		if ($page->id) {
			return static::update($page, $data);
		}

		if ($page->oldTemplate && $page->oldName) {
			$page = static::getByTemplateAndName($data->oldTemplate, $data->oldName);

			if ($page->id) {
				return static::update($page, $data);
			}
		}

		if ($page->oldName) {
			$page = static::getByTemplateAndName($data->template, $data->oldName);
			
			if ($page->id) {
				return static::update($page, $data);
			}
		}

		if ($page->oldTemplate) {
			$page = static::getByTemplateAndName($data->template, $data->name);

			if ($page->id) {
				return static::update($page, $data);
			}
		}
		return static::create($data);
	}

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return Pages
	 * @return Pages
	 */
	public static function getPwPages() {
		return self::pw('pages');
	}
	
}