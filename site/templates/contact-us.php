<?php
use Controllers\ContactUs;

$routes = [
	['GET',  '', ContactUs::class, 'index'],
	['POST', '', ContactUs::class, 'process'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
