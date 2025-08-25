<?php namespace App\Ecomm\PageManagers;
// App
use App\Ecomm\Pages\Templates\ProductsItemGroup as Template;


class ProductItemGroupsManager extends AbstractDplusPageManager {
    const TEMPLATE = Template::NAME;
    const PARENT_TEMPLATE = 'product-item-groups';
}