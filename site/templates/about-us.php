<?php
use Controllers\AboutUs;

$routes = [
	['GET',  '', AboutUs::class, 'index'],
	['POST', '', AboutUs::class, 'process'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
