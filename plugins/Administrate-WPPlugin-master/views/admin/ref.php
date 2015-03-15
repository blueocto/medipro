<h3><?php _e('Data Reference', 'administrate'); ?></h3>

<p><?php _e('The following tables can be used as reference for category, subcategory, and course shortcode attributes.', 'administrate'); ?></p>

<h4><?php _e('Courses', 'administrate'); ?></h4>
<table class="reference wp-list-table widefat" cellspacing="0">
	
	<thead>
		<tr>
			<th class="manage-column">Course Code</th>
			<th class="manage-column">Course Title</th>
			<th class="manage-column">ID</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th class="manage-column">Course Code</th>
			<th class="manage-column">Course Title</th>
			<th class="manage-column">ID</th>
		</tr>
	</tfoot>
	
	<tbody>
		<?php $alt = false; ?>
		<?php foreach ($this->plugin->make_api_call('get_courses') as $course) { ?>
			<tr<?php if ($alt) { ?> class="alternate"<?php } ?>>
				<th scope="row"><?= $course->get_code(); ?></th>
				<th><?= $course->get_title(); ?></th>
				<td><?= $course->get_id(); ?></td>
			</tr>
			<?php $alt = !$alt; ?>
		<?php } ?>
	</tbody>
	
</table>

<h4><?php _e('Categories', 'administrate'); ?></h4>
<table class="reference wp-list-table widefat" cellspacing="0">
	
	<thead>
		<tr>
			<th class="manage-column">Category</th>
			<th class="manage-column">ID</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th class="manage-column">Category</th>
			<th class="manage-column">ID</th>
		</tr>
	</tfoot>
	
	<tbody>
		<?php $alt = false; ?>
		<?php foreach ($this->plugin->make_api_call('get_categories') as $category) { ?>
			<tr<?php if ($alt) { ?> class="alternate"<?php } ?>>
				<th scope="row"><strong><?= $category->get_name(); ?></strong></th>
				<td><?= $category->get_id(); ?></td>
			</tr>
			<?php $alt = !$alt; ?>
			<?php foreach ($category->get_subcategories() as $subcategory)  {?>
				<tr<?php if ($alt) { ?> class="alternate"<?php } ?>>
					<th scope="row">&nbsp;&nbsp;&nbsp;&nbsp;<?= $subcategory->get_name(); ?></th>
					<td><?= $subcategory->get_id(); ?></td>
				</tr>
				<?php $alt = !$alt; ?>
			<?php } ?>
		<?php } ?>
	</tbody>
	
</table>