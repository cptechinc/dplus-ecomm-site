<?php

$config->js('config', [
	'ajax' => [
		'urls' => [
			'lookup' => $pages->searchLookupUrl(''),
			'json'   => $pages->jsonApiUrl(''),
		]
	],
]);

$config->js('vars', [
	'browser' => [
		'isMobile'  => $page->wire('browseragent')->isMobile(),
		'isAndroid' => $page->wire('browseragent')->isAndroid(),
		'isChrome'  => $page->wire('browseragent')->is('Chrome'),
	]
]);