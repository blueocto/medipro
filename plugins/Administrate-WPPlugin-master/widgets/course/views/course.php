<div id="<?= $this->add_namespace('', '-'); ?>" class="<?= $this->plugin->add_namespace('widget', '-'); ?>">

	<?php if ($this->plugin->page_is_generated()) { ?>
		
		<h2><?= $this->course->get_code(); ?>: <span><?= $this->course->get_title(); ?></span></h2>
	
		<?php include($this->plugin->get_path('/views/public/course_fields.php')); ?>
	
	<?php } ?>
	
	<?php 
	if ($this->plugin->to_boolean($this->plugin->get_option('show_course_events', 'course'))) {
		$this->plugin->display_widget(
			'event_table', 
			array(
				'course'			=>	$this->course->get_code(),
				'show_categories'	=>	false,
				'show_names'		=>	false,
				'show_codes'		=>	false
			)
		);
	}
	?>
	
	<ul class="<?= $this->add_namespace('menu', '-'); ?>">
		<li class="<?= $this->plugin->add_namespace('back', '-'); ?>"><a href="<?= get_permalink($coursePage); ?>"><?php _e('See More Courses', 'administrate'); ?></a></li>
	</ul>

</div>
