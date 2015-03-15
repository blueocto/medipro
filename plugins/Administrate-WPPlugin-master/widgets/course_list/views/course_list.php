<?php if (count($this->get_courses()) > 0) { ?>
	
	<ul id="<?= $this->add_namespace('', '-'); ?>" class="<?= $this->plugin->add_namespace('widget', '-'); ?>">
	
		<?php 
		$alt = false; 
		$altClass = $this->plugin->add_namespace('alt', '-');
		?>
		<?php foreach ($this->get_courses() as $course) { ?>		
			<?php if (!$this->plugin->filter_object_is_hidden('course', $course->get_code())) { ?>
				<li<?php if ($alt) { ?> class="<?= $altClass; ?>"<?php } ?>>
					<?php if (!empty($coursePage)) { ?>
						<a href="<?= $this->plugin->get_course_url($course); ?>">
							<?php if ($show_codes) { ?>
								<span class="<?= $this->plugin->add_namespace(array('course', 'code'), '-'); ?>"><?= $course->get_code(); ?>:</span>
							<?php } ?>
							<?= $course->get_title(); ?>
						</a>
					<?php } else { ?>
						<?php if ($show_codes) { ?>
							<span class="<?= $this->plugin->add_namespace(array('course', 'code'), '-'); ?>"><?= $course->get_code(); ?>:</span>
						<?php } ?>
						<?= $course->get_title(); ?>
					<?php } ?>
				</li>
				<?php $alt = !$alt; ?>
			<?php } ?>
		<?php } ?>
		
	</ul>

<?php } else { ?>

	<p><?php _e('There are no courses to show.', 'administrate'); ?></p>

<?php } ?>