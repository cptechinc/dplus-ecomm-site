<?php
use Controllers\Login;

$routes = [
	['GET',  '', Login::class, 'index'],
	['POST', '', Login::class, 'process'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-blank-page.php";
