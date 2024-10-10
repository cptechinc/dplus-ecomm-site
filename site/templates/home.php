<?php
use Controllers\Home;

$routes = [
	['GET',  '', Home::class, 'index'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page-condensed.php";
