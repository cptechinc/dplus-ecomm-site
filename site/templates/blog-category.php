<?php
use Controllers\Blog\BlogCategory;

$routes = [
	['GET',  '', BlogCategory::class, 'index'],
	['GET',  'page{pagenbr:\d+}', BlogCategory::class, 'index'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
