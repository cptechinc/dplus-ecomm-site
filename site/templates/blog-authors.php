<?php
use Controllers\Blog\BlogAuthors;
use Controllers\Blog\BlogAuthor;
use Controllers\Blog\BlogAuthorPosts;

$routes = [
	['GET',  '', BlogAuthors::class, 'index'],
	['GET',  '{name}', BlogAuthor::class, 'index'],
	['GET',  '{name}/posts', BlogAuthorPosts::class, 'index'],
	['GET',  '{name}/posts/page{pagenbr:\d+}', BlogAuthorPosts::class, 'index'],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";
