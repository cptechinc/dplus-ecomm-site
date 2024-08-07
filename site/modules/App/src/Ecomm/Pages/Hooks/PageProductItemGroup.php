<?php namespace App\Ecomm\Pages\Hooks;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\Page;
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\CodeTables\ItemGroup as ItemGroupTable;


/**
 * Product
 * Add hooks for Product
 * 
 * @static self $instance
 */
class PageProduct extends WireData {
	private static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */
	public static function instance() {
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

/* =============================================================
	Hooks
============================================================= */	
	/**
	 * Add hooks to get URLs
	 * @return void
	 */
	public function addHooks() {
		$this->addHookProperty("Page(template=products-item-group)::invgroup", function(HookEvent $event) {
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