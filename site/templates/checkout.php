<?php
use Controllers\Checkout;

$routes = [
	['GET',  '', Checkout::class, 'index'],
	['POST', '', Checkout::class, 'process'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
