<?php namespace App\Ecomm\PageManagers;
// ProcessWire
use ProcessWire\Page;
// App
use App\Ecomm\PageManagers\Data\PageData;
use App\Ecomm\Pages\Templates\Product as Template;


class ProductsManager extends AbstractDplusPageManager {
    const TEMPLATE = Template::NAME;
    const PARENT_TEMPLATE = 'products';
    const DPLUSID_FIELDNAME = 'itemid';

/* =============================================================
	CRUD Creates / Updates
============================================================= */
    protected static function setPageData(Page $p, PageData $data) : void {
		$p->itemid          = $data->dplusid;
		$p->itemdescription = $data->dplusdescription;
		$p->title            = $data->title;
		$p->name             = self::pwSanitizer()->pageName($data->name);
	}
}