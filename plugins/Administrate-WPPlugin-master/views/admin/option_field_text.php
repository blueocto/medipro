<input 
	type="<?= $field['type']; ?>" 
	name="<?= $field['optionsKey']; ?>[<?= $field['key']; ?>]" 
	id="<?= $field['key']; ?>" 
	value="<?= $currentValue; ?>"
	<?php if (array_key_exists("max_length", $field)) { ?>
		maxlength="<?= $field["max_length"]; ?>"
	<?php } ?>
	<?php if ($field["required"]) { ?>
		required="required"
	<?php } ?>
>
<?php if (isset($field['append'])) { ?>
	<span class="appendum"><?= $field['append']; ?></span>
<?php } ?>
<?php if (isset($field['hint'])) { ?>
	<span class="hint"><?= $field['hint']; ?></span>
<?php } ?>
