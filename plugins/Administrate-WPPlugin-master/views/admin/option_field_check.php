<?php
if (!array_key_exists('hint', $field)) {
	$field['hint'] = '';	
}
?>
<label for="<?= $field['key']; ?>"><input type="checkbox" name="<?= $field['optionsKey']; ?>[<?= $field['key']; ?>]" id="<?= $field['key']; ?>" value="1" <?php if ($currentValue == 1) { ?>checked="checked"<?php } ?>> <?= $field['hint']; ?></label>
