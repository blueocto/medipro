<ul id="<?= $this->add_namespace('', '-'); ?>" class="<?= $this->plugin->add_namespace('widget', '-'); ?>">

	<?php 
	$alt = false; 
	$altClass = $this->plugin->add_namespace('alt', '-');
	?>
	<?php foreach ($this->get_subcategories() as $subcategory) { ?>
		<?php if (!$this->plugin->filter_object_is_hidden('subcategory', $subcategory->get_id())) { ?>
			<li<?php if ($alt) { ?> class="<?= $altClass; ?>"<?php } ?>>
				<?php if (!empty($coursePage)) { ?>
					<a href="<?= $this->plugin->get_subcategory_url($subcategory); ?>"><?= $subcategory->get_name(); ?></a>
				<?php } else { ?>
					<?= $subcategory->get_name(); ?>
				<?php } ?>
			</li>
			<?php $alt = !$alt; ?>
		<?php } ?>
	<?php } ?>
	
</ul>
