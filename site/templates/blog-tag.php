<?php
use Controllers\Blog\BlogTag;

$routes = [
	['GET',  '', BlogTag::class, 'index'],
	['GET',  'page{pagenbr:\d+}', BlogTag::class, 'index'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
