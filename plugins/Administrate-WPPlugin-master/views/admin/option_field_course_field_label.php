<?php
$tmpField = $this->plugin->get_option_field($field['key'], 'course');
$values = get_option($field['optionsKey']);
?>

<table class="course_field_labels wp-list-table widefat" cellspacing="0">
	<thead>
		<tr>
			<th class="manage-column"><?php _e('Field', 'administrate'); ?></th>
			<th class="manage-column"><?php _e('Label', 'administrate'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th class="manage-column"><?php _e('Field', 'administrate'); ?></th>
			<th class="manage-column"><?php _e('Label', 'administrate'); ?></th>
		</tr>
	</tfoot>
	<tbody>
		<?php if (isset($tmpField['fields'])) { ?>
			<?php $alt = false; ?>
			<?php foreach ($tmpField['fields'] as $key=>$label) { ?>
				<?php 
				$option = $field['key'] . $this->plugin->get_key_delimiter() . $key; 
				$alt = !$alt;
				?>
				<tr<?php if ($alt) { ?> class="alternate"<?php } ?>>
					<th scope="row"><label for="<?= $option; ?>"><?= $label; ?></label></th>
					<td><input type="text" name="<?= $field['optionsKey']; ?>[<?= $option; ?>]" value="<?= $values[$option]; ?>" id="<?= $option; ?>" maxlength="<?= $field['max_length']; ?>"></td>
				</tr>
			<?php } ?>
		<?php } ?>
	</tbody>
</table>