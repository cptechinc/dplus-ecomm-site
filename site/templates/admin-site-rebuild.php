<?php
use Controllers\Admin\Rebuild;

$routes = [
	['GET', 'pw-components', Rebuild\PwComponents::class, 'index'],
	'pages' => [
		'products' => [
			['GET', '', Rebuild\Pages\Products::class, 'index'],
			['GET', 'item-groups', Rebuild\Pages\ProductsItemGroups::class, 'index'],
		]	
	],
];
$router = new Mvc\Routers\Json();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$response = $router->route();

echo json_encode($response);