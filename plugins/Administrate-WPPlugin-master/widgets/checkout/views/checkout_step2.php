<?php extract($params); ?>

<h3><?php _e('Attendee Details', 'administrate'); ?></h3>

<?php $this->plugin->display_errors($errors); ?>

<?php $fieldClass = $this->plugin->add_namespace('field', '-'); ?>

<fieldset id="<?= $this->add_namespace(array('buyer', 'details'), '-'); ?>">
	<legend><?php _e('Buyer Information', 'administrate'); ?></legend>
	<input type="hidden" name="order_buyer_details" value="">
	<?php foreach ($buyer_fields as $field) { ?>
		<?php if($field == 'notes' && !$show_notes) { continue; } ?>
		<?php $id = $this->add_namespace(array('buyer', str_replace('_', '-', $field)), '-');; ?>
		<div class="<?= $fieldClass; ?>">
			<label for="<?= $id; ?>"><?= $this->plugin->get_data_label('orders', 'order_buyer_details', $field); ?></label>
			<?php if($field == 'notes'): ?>
				<textarea name="order_buyer_details_<?= $field; ?>" id="<?= $id; ?>" maxlength="512" rows="5" cols="30"><?= $buyer_values[$field]; ?></textarea>
			<?php else: ?>
				<input type="text" name="order_buyer_details_<?= $field; ?>" id="<?= $id; ?>" value="<?= $buyer_values[$field]; ?>" maxlength="64">
			<?php endif; ?>
		</div>
	<?php } ?>
	<div class="<?= $fieldClass; ?> <?= $fieldClass; ?>-check" id="<?= $this->add_namespace(array('buyer', 'is', 'attendee', 'field'), '-'); ?>">
		<label><input type="checkbox" name="buyer_is_attendee" id="<?= $this->add_namespace(array('buyer', 'is', 'attendee'), '-'); ?>"> Buyer is also attendee.</label>
	</div>
</fieldset>

<fieldset id="<?= $this->add_namespace(array('invoice', 'address'), '-'); ?>">
	<legend><?php _e('Invoice Address', 'administrate'); ?></legend>
	<input type="hidden" name="order_invoice_address" value="">
	<?php foreach ($invoice_fields as $field) { ?>
		<?php $id = $this->add_namespace(array('invoice', str_replace('_', '-', $field)), '-'); ?>
		<div class="<?= $fieldClass; ?>">
			<label for="<?= $id; ?>"><?= $this->plugin->get_data_label('orders', 'order_invoice_address', $field); ?></label>
			<?php if ($field == 'country') { ?>
				<select name="order_invoice_address_<?= $field; ?>" id="<?= $id; ?>">
					<?php foreach ($countries as $code=>$name) { ?>
						<option
							value="<?= $code; ?>"
							<?php if ($code == $invoice_values[$field]) { ?>
								selected="selected"
							<?php } ?>
						><?= $name; ?></option>
					<?php } ?>
				</select>
			<?php } else { ?>
				<input type="text" name="order_invoice_address_<?= $field; ?>" id="<?= $id; ?>" value="<?= $invoice_values[$field]; ?>" maxlength="64">
			<?php } ?>
		</div>
	<?php } ?>
</fieldset>

<input type="hidden" name="order_attendee_details" value="">
<?php for ($i = 1; $i <= $num_attendees; ++$i) { ?> 
	<fieldset id="<?= $this->add_namespace(array('attendee', 'details', $i), '-'); ?>">
		<legend><?php _e('Attendee', 'administrate'); ?> <?= $i; ?></legend>
		<?php foreach ($attendee_fields as $field) { ?>
			<?php if($field == 'notes' && !$show_notes) { continue; } ?>
			<?php 
			$id = $this->add_namespace(array('attendee', str_replace('_', '-', $field), $i), '-'); 
			$value = '';
			if (isset($attendee_values[$field][$i-1])) {
				$value = $attendee_values[$field][$i-1];	
			}
			?>
			<div class="<?= $fieldClass; ?>">
				<label for="<?= $id; ?>"><?= $this->plugin->get_data_label('orders', 'order_attendee_details', $field); ?></label>
				<input type="text" name="order_attendee_details_<?= $field; ?>[]" id="<?= $id; ?>" value="<?= $value; ?>" maxlength="64">
			</div>
		<?php } ?>
	</fieldset>
<?php } ?>

<input type="submit" value="<?php _e('Continue', 'administrate'); ?>" class="<?= $this->plugin->add_namespace('btn', '-'); ?>">
