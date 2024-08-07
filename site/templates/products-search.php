<?php
use Controllers\Products\Search;

$routes = [
	['GET', '', Search::class, 'index'],
	['GET', 'page{pagenbr:\d+}', Search::class, 'index'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
