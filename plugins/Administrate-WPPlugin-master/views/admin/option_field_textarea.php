<?php 
if (!array_key_exists("height", $field)) {
	$field["height"] = 5;
}
if (!array_key_exists("width", $field)) {
	$field["width"] = 50;
}
?>

<?php if (isset($field['allow_html']) && $field['allow_html']) { ?>
	<?php
	wp_editor(
		$currentValue, 
		$field['key'],
		array(
			'media_buttons'		=>	false,
			'textarea_name'		=>	$field['optionsKey'] . '[' . $field['key'] . ']',
			'textarea_rows'		=>	$field['height'],
			'teeny'				=>	true
		)
	);
	?>
<?php } else { ?>
	<textarea 
		name="<?= $field['optionsKey']; ?>[<?= $field['key']; ?>]" 
		id="<?= $field['key']; ?>" 
		class="large-text code" 
		cols="<?= $field["width"]; ?>" 
		rows="<?= $field["height"]; ?>" 
		<?php if ($field["required"]) { ?>
			required="required"
		<?php } ?>
	><?= $currentValue; ?></textarea>
<?php } ?>
