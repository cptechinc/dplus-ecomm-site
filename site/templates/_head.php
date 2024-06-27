<?php include ('./_head-blank.php'); ?>

<div class="page">
	<?php if ($session->isLoggedInEcomm() === false) : ?>
		<?= $TWIG->render('nav/topbar.twig'); ?>
	<?php endif; ?>
	
	<header class="nav-holder make-sticky">
		<?= $TWIG->render('nav/navbar-example.twig'); ?> 
	</header>

