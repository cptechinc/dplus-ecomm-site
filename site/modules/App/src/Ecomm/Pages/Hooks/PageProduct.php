<?php namespace App\Ecomm\Pages\Hooks;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\Page;
// Dplus
use Dplus\Database\Tables\Item as ItemTable;
// App
use App\Ecomm\Pages\Templates\Product as Template;
use App\Ecomm\Services\Product\Pricing as PricingService;
use App\Pw\Hooks\AbstractStaticHooksAdder;


/**
 * Product
 * Add hooks for Product
 */
class PageProduct extends AbstractStaticHooksAdder {
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

		$m->addHook("Page(template=$tpl)::requestPricing", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;
			$SERVICE = PricingService::instance();
			$event->return = $SERVICE->sendRequestForOne($page->itemid);
		});

		$m->addHookProperty("Page(template=$tpl)::pricing", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;
			$SERVICE = PricingService::instance();
			if ($page->has('aPricing')) {
				$event->return = $page->aPricing;
				return true;
			}
			$SERVICE->sendRequestForOne($page->itemid);
			$page->aPricing = $SERVICE->pricing($page->itemid);
			$event->return  = $page->aPricing;
		});

		$m->addHookProperty("Page(template=$tpl)::itm", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;
			
			if ($page->has('aItm')) {
				$event->return = $page->aItm;
				return true;
			}
			$TABLE = ItemTable::instance();
			$page->aItm = $TABLE->item($page->itemid);
			$event->return = $page->aItm;
		});

		$m->addHookProperty("Page(template=$tpl)::listprice", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;

			if ($page->has('aListprice')) {
				$event->return = $page->aListprice;
				return true;
			}
			$page->aListprice = $page->itm->pricing->baseprice;
			$event->return = $page->aListprice;
		});

		$m->addHookProperty("Page(template=$tpl)::sellprice", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;

			if ($page->has('aSellprice')) {
				$event->return = $page->aSellprice;
				return true;
			}

			if ($event->user->isLoggedInEcomm() === false) {
				$event->return = $page->listprice;
				return true;
			}
			$page->aSellprice = $page->pricing->price;
			$event->return    = $page->aSellprice;
		});

		$m->addHookProperty("Page(template=$tpl)::qtyInStock", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;
			if ($page->has('aQtyInStock')) {
				$event->return = $page->aQtyInStock;
				return true;
			}
			$page->aQtyInStock = $page->pricing->qty;
			$event->return = $page->aQtyInStock;
		});
	}
}