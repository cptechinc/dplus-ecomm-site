<?php
use Controllers\Admin;

$routes = [
	['GET', '', Admin::class, 'index'],
	'pages' => [
		['GET', '', Admin\Pages\Menu::class, 'index'],
		'products-item-groups' => [
			['GET', '', Admin\Pages\ProductsItemGroups::class, 'index'],
			['GET',  'page{pagenbr:\d+}', Admin\Pages\ProductsItemGroups::class, 'index'],
		],
		'products' => [
			['GET', '', Admin\Pages\Products::class, 'index'],
			['GET',  'page{pagenbr:\d+}', Admin\Pages\Products::class, 'index'],
		]
	]
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
