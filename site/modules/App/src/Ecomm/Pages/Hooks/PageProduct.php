<?php namespace App\Ecomm\Pages\Hooks;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\Page;
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\Item as ItemTable;
// App
use App\Ecomm\Services\Product\Pricing as PricingService;


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
		$this->addHook("Page(template=product)::requestPricing", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;
			$SERVICE = PricingService::instance();
			$event->return = $SERVICE->sendRequestForOne($page->itemid);
		});

		$this->addHookProperty("Page(template=product)::pricing", function(HookEvent $event) {
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

		$this->addHookProperty("Page(template=product)::itm", function(HookEvent $event) {
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

		$this->addHookProperty("Page(template=product)::listprice", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;

			if ($page->has('aListprice')) {
				$event->return = $page->aListprice;
				return true;
			}
			$page->aListprice = $page->itm->pricing->baseprice;
			$event->return = $page->aListprice;
		});

		$this->addHookProperty("Page(template=product)::sellprice", function(HookEvent $event) {
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
			$page->aSellprice = $page->listprice;
			$event->return    = $page->aSellprice;
		});

		$this->addHookProperty("Page(template=product)::qtyInStock", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;
			if ($page->has('aQtyInStock')) {
				$event->return = $page->aQtyInStock;
				return true;
			}
			$page->aQtyInStock = $page->pricing->price;
			return $page->aQtyInStock;
		});
	}
}