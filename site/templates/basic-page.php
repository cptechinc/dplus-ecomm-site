<?php include ('./_head.php'); ?>
	<div class="container mb-4 main">
		<?= $page->html ? $page->html : $page->body; ?>
	</div>
<?php include ('./_foot.php'); ?>
