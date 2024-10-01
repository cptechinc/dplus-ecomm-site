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
	'orders' => [
		['GET',  '', Account\Orders\Orders::class, 'index'],
		['GET',  '{ordn:\d+}/', Account\Orders\Order::class, 'index'],
		['GET',  '{ordn:\d+}/documents/', Account\Orders\OrderDocuments::class, 'index'],
		['GET',  'page{pagenbr:\d+}/', Account\Orders\Orders::class, 'index'],
	],
	'history' => [
		['GET',  '', Account\Orders\HistoryList::class, 'index'],
		['GET',  '{ordn:\d+}/', Account\Orders\HistoryOrder::class, 'index'],
		['GET',  '{ordn:\d+}/documents/', Account\Orders\HistoryOrderDocuments::class, 'index'],
		['GET',  'page{pagenbr:\d+}/', Account\Orders\HistoryList::class, 'index'],
	],
	'invoices' => [
		'open' => [
			['GET',  '', Account\Invoices\OpenInvoices::class, 'index'],
			['GET',  'page{pagenbr:\d+}/', Account\Invoices\OpenInvoices::class, 'index'],
		],
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