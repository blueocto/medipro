<div class="<?= $this->add_namespace('', '-'); ?> <?= $this->plugin->add_namespace('widget', '-'); ?>">

	<?php foreach ($groups as $group) { ?>
	
		<?php if (!empty($group['title'])) { ?>
			<h3><?= $group_title_pre . $group['title'] . $group_title_post; ?></h3>
		<?php } ?>
	
		<<?= $listTag; ?>>
		
			<?php 
			$alt = false; 
			$altClass = $this->plugin->add_namespace('alt', '-');
			?>
			<?php foreach ($group['events'] as $event) { ?>
				
				<?php if (!$this->plugin->filter_object_is_hidden('course', $event->get_course_code())) { ?>
				
					<?php
					$name = '';
					if ($group_by == 'course') {
						$name = $this->plugin->format_date_span($event->get_dates());
					} else {
						if ($show_codes) {
							$name .= '<span>' . $event->get_course_code() . ':</span> ';
						}
						$name .= $event->get_course_title();
					}
					?>
					
					<<?= $itemTag; ?><?php if ($alt) { ?> class="<?= $altClass; ?>"<?php } ?>>
						<?php if (!empty($registrationPage)) { ?>
							<a href="<?= $this->plugin->get_course_url($event->get_course()); ?>"><?= $name; ?></a>
						<?php } else { ?>
							<?= $name; ?>
						<?php } ?>
					</<?= $itemTag; ?>>
					
					<?php if ($listTag == 'dl') { ?>
						<?php if ($show_dates && ($group_by != 'course')) { ?>
							<dd<?php if ($alt) { ?> class="<?= $altClass; ?>"<?php } ?>><?= $this->plugin->format_date_span($event->get_dates()); ?></dd>
						<?php } ?>
						<?php if ($show_dates && ($group_by != 'course') && $show_times) { ?>
							<dd<?php if ($alt) { ?> class="<?= $altClass; ?>"<?php } ?>><?= $this->plugin->format_time_span($event->get_times()); ?></dd>
						<?php } ?>
						<?php if ($show_locations && ($group_by != 'location')) { ?>
							<dd<?php if ($alt) { ?> class="<?= $altClass; ?>"<?php } ?>><?= $event->get_location(); ?></dd>
						<?php } ?>
					<?php }  ?>
					
					<?php $alt = !$alt; ?>
				
				<?php } ?>
					
			<?php } ?>
			
		</<?= $listTag; ?>>
	
	<?php } ?>
	
</div>
