<?php
//  If no options were passed, default to no options
if (!array_key_exists("options", $field)) {
	$field['options'] = array();	
}

//  If the options field is a string, see if it matches any predefined option sets
if (is_string($field['options'])) {
	$set = $field['options'];
	$field['options'] = array();
	if ($set == 'pages') {
		$pages = get_pages();
		$field['options'][0] = __('none (do not link to separate page)', 'administrate');
		foreach ($pages as $page) {
			$field['options'][$page->ID] = $page->post_title;	
		}
	} else if ($set == 'countries') {
		require_once($this->plugin->get_path('/widgets/checkout/AdministrateWidgetCheckout.php'));
		$checkout = new AdministrateWidgetCheckout($this->plugin);
		foreach ($checkout->get_countries() as $code=>$name) {
			$field['options'][$code] = $name;	
		}
	}
}
?>

<select name="<?= $field['optionsKey']; ?>[<?= $field['key']; ?>]" id="<?= $field['key']; ?>">
	<?php foreach ($field['options'] as $value=>$display) { ?>
		<option value="<?= $value; ?>"
		<?php if ($value == $currentValue) { ?>
			selected="selected"
		<?php } ?>
		><?= $display; ?></option>
	<?php } ?>
</select>

<?php if (isset($field['hint'])) { ?>
	<span class="hint"><?= $field['hint']; ?></span>
<?php } ?>