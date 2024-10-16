<?php
use Controllers\Blog;

$routes = [
	['GET',  '', Blog::class, 'index'],
	['GET',  'page{pagenbr:\d+}', Blog::class, 'index'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
