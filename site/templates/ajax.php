<?php
use Controllers\Ajax\Lookup;

$routes = [
	'lookup' => [
		'user' => [
			'shipping-addresses' => [
				['GET', '', Lookup\User::class, 'shippingAddresses'],
				['GET', 'page{pagenbr:\d+}', Lookup\User::class, 'shippingAddresses'],
			]
		]
	]
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

if ($config->ajax) {
	echo $page->html;
} else {
	include __DIR__ . "/basic-page.php";
}