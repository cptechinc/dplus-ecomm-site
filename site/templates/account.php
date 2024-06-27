<?php
use Controllers\Account;

$routes = [
	['GET',  '', Account::class, 'index'],
	'change-password' => [
		['GET',  '', Account\ChangePassword::class, 'index'],
		['POST', '', Account\ChangePassword::class, 'process'],
	],
	['GET', 'dashboard', Account\Dashboard::class, 'index'],
	'first-login' => [
		['GET',  '', Account\FirstLogin::class, 'index'],
		['POST', '', Account\FirstLogin::class, 'process'],
	],
	'forgot-password' => [
		['GET',  '', Account\ForgotPassword::class, 'index'],
		['POST', '', Account\ForgotPassword::class, 'process'],
	],
	'register' => [
		['GET',  '', Account\Register::class, 'index'],
		['POST', '', Account\Register::class, 'process'],
	],
];
$router = new Mvc\Routers\Router();
$router->setRoutes($routes);
$router->setRoutePrefix($page->url);
$page->html = $router->route();

include __DIR__ . "/basic-page.php";