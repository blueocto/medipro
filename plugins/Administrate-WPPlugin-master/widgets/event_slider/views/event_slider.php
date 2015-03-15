<div class="<?= $this->add_namespace('', '-'); ?> <?= $this->plugin->add_namespace('widget', '-'); ?>" data-slides-per-group="<?= $items_per_group; ?>">

	<div class="<?= $this->add_namespace('slides', '-'); ?>">
		
		<ul>
			
			<?php 
			$alt = false; 
			$altClass = $this->plugin->add_namespace('alt', '-');
			$i = 1;
			?>
			<?php foreach ($events as $event) { ?><?php if (!$this->plugin->filter_object_is_hidden('course', $event->get_course_code()) && ($show_sold_out || !$event->is_sold_out($translateNumPlaces)) && ($show_today || !$event->is_today())) { ?><li>
				<dl>
					<dt><?= $event->get_course_title(); ?></dt>
						<?php if ($show_dates) { ?>
							<dd class="date"><?= $this->plugin->format_date_span($event->get_dates()); ?></dd>
						<?php } ?>
						<?php if ($show_dates && $show_times) { ?>
							<dd class="time"><?= $this->plugin->format_time_span($event->get_times()); ?></dd>
						<?php } ?>
						<?php if ($show_locations) { ?>
							<dd><?= $event->get_location(); ?></dd>
						<?php } ?>
						<?php if ($show_places)  { ?>
							<?php if ($event->is_sold_out($translateNumPlaces)) { ?>
								<?php _e('Sold Out', 'administrate'); ?>
							<?php } else { ?>
								<?= $event->get_num_places(); ?> <?= $this->plugin->pluralize_str($event->get_num_places(), __('Place', 'administrate'), __('Places', 'administrate')) . __(' Available!', 'administrate'); ?>
							<?php } ?>
						<?php } ?>
				</dl>
				<?php if (!empty($registrationPage) && !$event->is_sold_out($translateNumPlaces)) { ?>
					<p class="<?= $this->add_namespace('book', '-'); ?>"><a href="<?= $this->plugin->get_registration_url($event->get_id()); ?>"><?php _e('BOOK NOW', 'administrate'); ?></a></p>
				<?php } ?>
				<?php 
				$alt = !$alt; 
				++$i;
				?>
			</li><?php } ?><?php } ?>
			
		</ul>
	
	</div>
	
	<ul class="<?= $this->add_namespace('nav', '-'); ?>">
		<li class="prev"><a href="#" title="previous"><?php _e('&lt;', 'administrate'); ?></a></li>
		<li class="next"><a href="#" title="next"><?php _e('&gt;', 'administrate'); ?></a></li>
	</ul>
	
</div>
