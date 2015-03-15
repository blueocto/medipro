<div class="<?= $this->add_namespace('errors', '-'); ?>">
	<p><?php _e('Please resolve the following issues before proceeding:', 'administrate'); ?></p>
	<ul>
		<?php foreach ($errors as $error) { ?>
			<li><?= $error; ?></li>
		<?php } ?>
	</ul>
</div>