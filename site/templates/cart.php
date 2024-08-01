<?php
use Controllers\Cart;

$routes = [
	['GET',  '', Cart::class, 'index'],
	['POST', '', Cart::class, 'process'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
