<?php namespace App\Ecomm\Pages\Hooks;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\Page;
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\CodeTables\ItemGroup as ItemGroupTable;
// App
use App\Ecomm\Pages\Templates\ProductsItemGroup as Template;
use App\Pw\Hooks\AbstractStaticHooksAdder;

/**
 * PageProductsItemGroup
 * Add hooks for Products Item Group Page
 */
class PageProductsItemGroup extends AbstractStaticHooksAdder {

/* =============================================================
	Hooks
============================================================= */	
	/**
	 * Add hooks to get URLs
	 * @return void
	 */
	public static function addHooks() {
		$m = self::pwModuleApp();
		$tpl = Template::NAME;

		$m->addHookProperty("Page(template=$tpl)::invgroup", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;
			
			if ($page->has('aInvgroup')) {
				$event->return = $page->aInvgroup;
				return true;
			}
			$TABLE = ItemGroupTable::instance();
			$page->aInvgroup = $TABLE->fetch($page->dplusid);
			$event->return = $page->aInvgroup;
		});
	}
}