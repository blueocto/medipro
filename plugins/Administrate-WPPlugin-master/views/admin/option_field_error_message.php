<?php
$tmpField = $this->plugin->get_option_field($field['key'], $field['groupKey']);
$values = get_option($field['optionsKey']);
?>

<table class="error_messages wp-list-table widefat" cellspacing="0">
	<thead>
		<tr>
			<th class="manage-column"><?php _e('Error', 'administrate'); ?></th>
			<th class="manage-column"><?php _e('Label', 'administrate'); ?></th>
			<th class="manage-column"><?php _e('Notes', 'administrate'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th class="manage-column"><?php _e('Error', 'administrate'); ?></th>
			<th class="manage-column"><?php _e('Label', 'administrate'); ?></th>
			<th class="manage-column"><?php _e('Notes', 'administrate'); ?></th>
		</tr>
	</tfoot>
	<tbody>
		<?php if (isset($tmpField['fields'])) { ?>
			<?php $alt = false; ?>
			<?php foreach ($tmpField['fields'] as $key=>$properties) { ?>
				<?php 
				$option = $field['key'] . $this->plugin->get_key_delimiter() . $key; 
				$alt = !$alt;
				?>
				<tr<?php if ($alt) { ?> class="alternate"<?php } ?>>
					<th scope="row"><label for="<?= $option; ?>"><?= $properties['label']; ?></label></th>
					<td>
						<input 
							type="text" 
							name="<?= $field['optionsKey']; ?>[<?= $option; ?>]" 
							value="<?= $values[$option]; ?>" 
							id="<?= $option; ?>" 
							<?php if (array_key_exists('max_length', $properties)) { ?>
								maxlength=""
							<?php } ?>
						></td>
					<td><?= $properties['hint']; ?></td>
				</tr>
			<?php } ?>
		<?php } ?>
	</tbody>
</table>