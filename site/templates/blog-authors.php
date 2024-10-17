<?php
use Controllers\Blog\BlogAuthors;
use Controllers\Blog\BlogAuthor;

$routes = [
	['GET',  '', BlogAuthors::class, 'index'],
	['GET',  '{name}', BlogAuthor::class, 'index'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
