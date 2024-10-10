<?php include ('./_head.php'); ?>
	<div class="main">
		<?= $page->html ? $page->html : $page->body; ?>
	</div>
<?php include ('./_foot.php'); ?>
