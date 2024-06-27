<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?= $page->get('headline|title'); ?></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="robots" content="all,follow">
		<meta name="format-detection" content="telephone=no">

		<?php foreach($config->styles->unique() as $css) : ?>
			<link rel="stylesheet" type="text/css" href="<?= $css; ?>" />
		<?php endforeach; ?>
		<?php if ($site->siteicon) : ?>
			<!-- Favicon and apple touch icons-->
			<link rel="shortcut icon" href="<?= $site->siteicon->url; ?>" type="image/x-icon">
			<link rel="apple-touch-icon" href="<?= $site->siteiconimage->url; ?>">
			<link rel="icon" href="<?= $site->siteiconimage->url; ?>">
		<?php endif; ?>
	<!-- Tweaks for older IEs--><!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
	</head>
	<body>