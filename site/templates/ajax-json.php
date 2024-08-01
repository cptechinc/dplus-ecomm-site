<?php
use Controllers\Ajax\Json\Cart;

$routes = [
	'cart' => [
		'add' => [
			['GET', '', Cart::class, 'addToCart'],
			['POST', '', Cart::class, 'addToCart'],
		]
	]
];
$router = new Mvc\Routers\Json();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$response = $router->route();

echo json_encode($response);