<?php
use Controllers\Products\ItemGroups;

$routes = [
	['GET',  '', ItemGroups::class, 'index'],
	['GET', 'page{pagenbr:\d+}', ItemGroups::class, 'index'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
