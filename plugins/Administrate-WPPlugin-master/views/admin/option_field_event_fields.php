<?php
$tmpField = $this->plugin->get_option_field('course_field_label', 'course');
$values = get_option($field['optionsKey']);
if (!array_key_exists('page_fields', $values) || empty($values['page_fields'])) {
	$values['page_fields'] = array();	
} else {
	$values['page_fields'] = unserialize($values['page_fields']);
}
$labels = $this->plugin->get_option_field('course_field_label', 'course');
?>

<table class="course_fields wp-list-table widefat" cellspacing="0">
	<thead>
		<tr>
			<th class="manage-column"><?php _e('Field', 'administrate'); ?></th>
			<th class="check manage-column"><?php _e('Show on Event Page', 'administrate'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th class="manage-column"><?php _e('Field', 'administrate'); ?></th>
			<th class="check manage-column"><?php _e('Show on Event Page', 'administrate'); ?></th>
		</tr>
	</tfoot>
	<tbody>
		<?php if (isset($tmpField['fields'])) { ?>
			<?php $alt = false; ?>
			<?php foreach ($labels['fields'] as $key=>$label) { ?>
				<?php 
				$option = $field['key'] . $this->plugin->get_key_delimiter() . $key; 
				$alt = !$alt;
				?>
				<tr<?php if ($alt) { ?> class="alternate"<?php } ?>>
					<th scope="row"><?= $label; ?></th>
					<td class="check"><input type="checkbox" name="<?= $field['optionsKey']; ?>[page_fields][]" value="<?= $key; ?>" <?php if (in_array($key, $values['page_fields'])) { ?>checked="checked"<?php } ?>></td>
				</tr>
			<?php } ?>
		<?php } ?>
	</tbody>
</table>