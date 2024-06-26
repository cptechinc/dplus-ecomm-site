<?php
use Controllers\Account;

$routes = [
	['GET',  '', Account::class, 'index'],
	'first-login' => [
		['GET',  '', Account\FirstLogin::class, 'index'],
		['POST', '', Account\FirstLogin::class, 'process'],
	]
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->body = $router->route();

include __DIR__ . "/basic-blank-page.php";