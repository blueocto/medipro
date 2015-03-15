<ul id="<?= $this->add_namespace('', '-'); ?>" class="<?= $this->plugin->add_namespace('widget', '-'); ?>">

	<?php foreach ($this->get_categories() as $category) { ?>
		<?php if (!$this->plugin->filter_object_is_hidden('category', $category->get_id())) { ?>
			<li>
				<?php if (!empty($coursePage)) { ?>
					<a href="<?= $this->plugin->get_category_url($category); ?>"><?= $category->get_name(); ?></a>
				<?php } else { ?>
					<?= $category->get_name(); ?>
				<?php } ?>
				<?php if ($show_subcategories) { ?>
					<?php $subcategories = $category->get_subcategories(); ?>
					<?php if (count($subcategories) > 0) { ?>
						<ul class="<?= $this->plugin->add_namespace(array('subcategory', 'list'), '-'); ?>">
							<?php 
							$alt = false; 
							$altClass = $this->plugin->add_namespace('alt', '-');
							?>
							<?php foreach ($subcategories as $subcategory) { ?>
								<?php if (!$this->plugin->filter_object_is_hidden('subcategory', $subcategory->get_id())) { ?>
									<li<?php if ($alt) { ?> class="<?= $altClass; ?>"<?php } ?>>
										<?php if (!empty($coursePage)) { ?>
											<a href="<?= $this->plugin->get_subcategory_url($subcategory, $category); ?>"><?= $subcategory->get_name(); ?></a>
										<?php } else { ?>
											<?= $subcategory->get_name(); ?>
										<?php } ?>							
									</li>
								<?php } ?>
								<?php $alt = !$alt; ?>
							<?php } ?>
						</ul>
					<?php } ?>
				<?php } ?>
			</li>
		<?php } ?>
	<?php } ?>
	
</ul>
