<?php extract($params); ?>

<h3><?php _e('Register for this Course', 'administrate'); ?></h3>

<?php $this->plugin->display_errors($errors); ?>

<?php include($this->plugin->get_path('/views/public/course_fields.php')); ?>

<table>
	<thead>
		<tr>
			<th><?php _e('Course', 'administrate'); ?></th>
			<th>
				<?php _e('Price per Person', 'administrate'); ?>
				<span class="<?= $this->add_namespace(array('price', 'qualifier'), '-'); ?>">
					<?php $this->plugin->display_currency_selector($this->get_order_event()); ?>
					<?php if (!empty($tax_label)) { ?>
						<?php if ($prices_include_taxes) { ?>
							<abbr title="<?php _e('including', 'administrate'); ?>"><?php _e('inc.', 'administrate'); ?></abbr>
						<?php } else { ?>
							<abbr title="<?php _e('excluding', 'administrate'); ?>"><?php _e('exc.', 'administrate'); ?></abbr>
						<?php } ?>
						<?= $tax_label; ?>
					<?php } ?>
				</span>
			</th>
			<th><?php _e('Attendees', 'administrate'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th><?= $course; ?></th>
			<td><?php $this->plugin->display_currency_prices($this->get_order_event(), $pricing_basis, $default_currency, $show_currency_indicator, $currency_indicator); ?></td>
			<td>
				<select name="order_num_attendees">
					<?php
					$maxAttendees = intval($this->plugin->get_option('max_attendees', 'checkout'));
					if ($maxAttendees === 0) {
						$maxAttendees = $event->get_num_places(); 	
					}
					?>
					<?php for ($i = 1; $i <= $maxAttendees; ++$i) { ?>
						<option
							value="<?= $i; ?>"
							<?php if ($i == $num_attendees) { ?>
								selected="selected"
							<?php } ?>
						><?= $i; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>	

<input type="submit" value="<?php _e('Register Now', 'administrate'); ?>" class="<?= $this->plugin->add_namespace('btn', '-'); ?>">
