<?php namespace App\Ecomm\Pages\Hooks;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\Page;
use ProcessWire\WireData;
// Dplus
use Dplus\Database\Tables\Item as ItemTable;
// App
use App\Ecomm\Database\Pricing as PricingTable;
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
			if ($SERVICE->exists($page->itemid) === false) {
				$SERVICE->sendRequestForOne($page->itemid);
			}
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
			$page->aQtyInStock = $page->pricing ? $page->pricing->qty : 0;
			$event->return = $page->aQtyInStock;
		});

		$m->addHookProperty("Page(template=$tpl)::pricebreaks", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;

			if ($page->has('aPricebreaks')) {
				$event->return = $page->aPricebreaks;
				return true;
			}

			$colPrice = 'priceprice';
			$colQty   = 'priceqty';
			$pricebreaks = [];

			for ($i = 1; $i <= PricingTable::NBR_PRICEBREAKS; $i++) {
				$colQtyBreak = $colQty . $i;
				$colQtyPrice = $colPrice . $i;

				if ($page->pricing->$colQtyBreak == 0) {
					continue;
				}
				$pricebreaks[] = [
					'qty' => $page->pricing->$colQtyBreak,
					'price' => $page->pricing->$colQtyPrice
				];
			}

			$page->aPricebreaks = $pricebreaks;
			$event->return = $page->aPricebreaks;
		});

		$m->addHook("Page(template=$tpl)::hasPricebreaks", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;
			$event->return = empty($page->pricebreaks) === false;
		});

		$m->addHookProperty("Page(template=$tpl)::productData", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;

			$pData = new WireData();
			$pData->itemid = $page->itemid;
			$pData->description = $page->itemdescription;
			$pData->itemdescription = $page->itemdescription;
			$pData->listprice       = $page->listprice;
			$pData->sellprice       = $page->sellprice;
			$pData->qtyInStock      = $page->qtyInStock;
			$pData->pricebreaks     = $page->pricebreaks;
			$pData->weight          = $page->itm->weight;
			$pData->uom             = $page->itm->unitofmsale->code;
			$pData->isStockedByCaseWeight = $page->itm->unitofmsale->isStockedByCaseWeight();
			$event->return = $pData;
		});
	}
}