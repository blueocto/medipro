<?php
//  Initialize step definitions
$steps = array(
	1	=>	__('Course', 'administrate'),
	2	=>	__('Attendees', 'administrate'),
	3	=>	__('Terms', 'administrate'),
	4	=>	__('Confirm &amp; Pay', 'administrate')
);

//  Loop through steps and set empty content for any that don't exist
foreach ($steps as $num=>$title) {
	if (!isset($step_content[$num])) {
		$step_content[$num] = '';
	}
}

//  If the completeContent variable isn't set, default to empty
if (!isset($complete_content)) {
	$complete_content = '';	
}

//  If this is less than step 5, set the next step to current step plus 1
if ($current_step < 5) {
	$next_step = $current_step + 1;
}
?>

<div id="<?= $this->add_namespace('', '-'); ?>" class="<?= $this->plugin->add_namespace('widget', '-'); ?>">
	<h2>
		<?= $this->widgetTitle; ?> 
		<?php if ($current_step < 5) { ?>
			<span id="<?= $this->add_namespace('reset', '-'); ?>">[<a href="<?= $this->_get_reset_url(); ?>"><?php _e('change course', 'administrate'); ?></a>]</span>
		<?php } ?>
	</h2>
	<?php if ($current_step < 5) { ?>
		<form action="<?= $formAction; ?>" method="post">
			<input type="hidden" name="<?= $this->add_namespace('step'); ?>" value="<?= $next_step; ?>" id="<?= $this->add_namespace(array('next', 'step'), '-'); ?>">
			<input type="hidden" name="<?= $this->orderKey; ?>" value="<?= $this->order->get_id(); ?>">
			<ol class="ui-tabs-nav">
				<?php foreach ($steps as $step=>$title) { ?>
					<?php
					//  Set the CSS class
					$tabClass = 'ui-state-default';
					if ($step == $current_step) {
						$tabClass = 'ui-state-active';
					} else if ($step > $this->order->get_max_step()) {
						$tabClass = 'ui-state-disabled';
					}
					
					//  Set the link HREF
					$href = $this->get_step_url($step);
					?>
					<li class="<?= $tabClass; ?>"><a href="<?= $href; ?>"><?= $step; ?>: <?= $title; ?></a></li>
				<?php } ?>
			</ol>
			<?php foreach ($steps as $step=>$title) { ?>
				<div id="<?= $this->add_namespace(array('step', $step), '-'); ?>" class="<?= $this->add_namespace('step', '-'); ?><?php if ($step != $current_step) { ?> <?= $this->plugin->add_namespace('hidden', '-'); ?><?php } ?>"><?= $step_content[$step]; ?></div>
			<?php } ?>
		</form>
	<?php } ?>
	<div id="<?= $this->add_namespace('complete', '-'); ?>"<?php if ($current_step != 5) { ?> class="<?= $this->plugin->add_namespace('hidden', '-'); ?>"<?php } ?>><?= $complete_content; ?></div>
	<?php include($this->plugin->get_path('/views/public/widget_footer.php')); ?>
</div>
