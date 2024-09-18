<?php
$twig = $modules->get('Twig')->twig;
$page->html = $twig->render('errors/404.twig');

include __DIR__ . "/basic-page.php";
