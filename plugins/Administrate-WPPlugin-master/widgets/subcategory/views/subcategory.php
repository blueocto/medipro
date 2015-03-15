<div id="<?= $this->add_namespace('', '-'); ?>" class="<?= $this->plugin->add_namespace('widget', '-'); ?>">

	<?php if ($this->plugin->page_is_generated()) { ?>
	
		<h2><?= $this->subcategory->get_name(); ?></h2>
	
		<div class="<?= $this->add_namespace('description', '-'); ?>">
			<?= $this->subcategory->get_description(); ?>
		</div>
	
	<?php } ?>
	
	<?php if ($this->plugin->to_boolean($this->plugin->get_option('show_course_links', 'course')) && count($this->get_courses()) > 0) { ?>
		<h3><?php _e('Courses', 'administrate'); ?></h3>
		<?php 
		$this->plugin->display_widget(
			'course_list', 
			array(
				'subcategory'	=>	$this->subcategory->get_id(),
				'show_codes'	=>	$show_codes
			)
		); 
		?>
	<?php } ?>
	
	<ul class="<?= $this->plugin->add_namespace(array('course', 'menu'), '-'); ?>">
		<li class="<?= $this->plugin->add_namespace('back', '-'); ?>"><a href="<?= get_permalink($coursePage); ?>"><?php _e('See All Categories', 'administrate'); ?></a></li>
	</ul>

</div>
