<select name="<?= $fieldName; ?>" class="<?= $this->add_namespace('currencies', '-'); ?>">
	<?php foreach ($currencies as $currency) { ?>
		<option
			value="<?= $currency; ?>"
			<?php if ($currency == $defaultCurrency) { ?>
				selected="selected"
			<?php } ?>
		><?= $currency; ?></option>
	<?php } ?>
</select>
