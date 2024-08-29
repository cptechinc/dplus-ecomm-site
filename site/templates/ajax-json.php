<?php
use Controllers\Ajax\Json\Cart;
use Controllers\Ajax\Json\Checkout;
use Controllers\Ajax\Json\Products;

$routes = [
	'cart' => [
		'add' => [
			['GET', '', Cart::class, 'addToCart'],
			['POST', '', Cart::class, 'addToCart'],
		]
	],
	'checkout' => [
		['GET', '', Checkout::class, 'process'],
		['POST', '', Checkout::class, 'process'],
	],
	'products' => [
		'validate' => [
			['GET', 'itemid', Products::class, 'validateItemid'],
		]
	],
];
$router = new Mvc\Routers\Json();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$response = $router->route();

echo json_encode($response);