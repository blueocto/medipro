<div id="<?= $this->add_namespace('', '-'); ?>" class="<?= $this->plugin->add_namespace('widget', '-'); ?>">

	<h2><?= $this->event->get_course_title(); ?>: <span><?= $this->plugin->format_date_span($this->event->get_dates()); ?></span></h2>
	
	<?php include($this->plugin->get_path('/views/public/course_fields.php')); ?>
	
	<ul class="<?= $this->add_namespace('menu', '-'); ?>">
		<li class="<?= $this->plugin->add_namespace('back', '-'); ?>"><a href="<?= get_permalink($registrationPage); ?>"><?php _e('Go Back', 'administrate'); ?></a></li>
		<?php if (!empty($registrationPage)) { ?>
			<li class="<?= $this->plugin->add_namespace('register', '-'); ?>"><a href="<?= $this->plugin->get_registration_url($this->event->get_id()); ?>"><?php _e('Register Now', 'administrate'); ?></a></li>
		<?php } ?>
	</ul>

</div>
