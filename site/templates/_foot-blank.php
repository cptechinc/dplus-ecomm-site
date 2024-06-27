		<?= $TWIG->render('util/ajax-modal.twig'); ?>

		<?php foreach($config->scripts->unique() as $script) : ?>
			<script src="<?= $script; ?>"></script>
		<?php endforeach; ?>

		<script>		
			<?php foreach ($config->js('vars') as $var => $data) : ?>
				let <?= $var; ?> = <?= json_encode($data); ?>;
			<?php endforeach; ?>
		</script>
	</body>
</html>
