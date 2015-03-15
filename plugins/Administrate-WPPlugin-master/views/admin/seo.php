<?php 
//  Include the course filter
require_once($this->plugin->get_path('/AdministrateCourseFilter.php'));
?>

<h3><?php _e('SEO & Filters', 'administrate'); ?></h3>

<p><?php _e('The following tables can be used to set course, subcategory, and category URLs, keywords, and descriptions for better search engine optimization. You may also use this screen to hide objects from the website so that they do not display. <strong>Note that these special URL strings will only be used if a custom course URL structure is selected on the "Courses" tab.</strong>', 'administrate'); ?></p>

<form action="admin.php?page=<?= $_GET['page']; ?>" method="post">

	<h4><?php _e('Courses', 'administrate'); ?></h4>
	<table class="<?= $this->plugin->add_namespace(array('seo', 'table'), '-'); ?> wp-list-table widefat" cellspacing="0">
		
		<thead>
			<tr>
				<th class="manage-column">Course Code</th>
				<th class="manage-column">Course Title</th>
				<th class="manage-column">URL String</th>
				<th class="manage-column">Keywords</th>
				<th class="manage-column">Description</th>
				<th class="check manage-column">Hide</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th class="manage-column">Course Code</th>
				<th class="manage-column">Course Title</th>
				<th class="manage-column">URL String</th>
				<th class="manage-column">Keywords</th>
				<th class="manage-column">Description</th>
				<th class="check manage-column">Hide</th>
			</tr>
		</tfoot>
		
		<tbody>
			<?php $alt = false; ?>
			<?php foreach ($this->plugin->make_api_call('get_courses') as $course) { ?>
				<?php
				//  Attempt to query for the filter
				$filter = new AdministrateCourseFilter($this->plugin, array('filter_object_type'=>'course', 'filter_object_id'=>$course->get_code()));
				
				//  If the filter doesn't exist, create it
				if (!$filter->exists()) {
					$filter->create(array(
						'filter_object_type'	=>	'course',
						'filter_object_id'		=>	$course->get_code(),
						'filter_url_string'		=>	$course->get_code() . ' ' . $course->get_title()
					));
				}
				?>
				<input type="hidden" name="ids[]" value="<?= $filter->get_id(); ?>">
				<tr<?php if ($alt) { ?> class="alternate"<?php } ?>>
					<th scope="row"><?= $course->get_code(); ?></th>
					<th><?= $course->get_title(); ?></th>
					<td class="long"><input type="text" name="url_strings[]" value="<?= $filter->get_url_string(); ?>" maxlength="256"></td>
					<td class="long"><input type="text" name="keywords[]" value="<?= $filter->get_keywords(); ?>" maxlength="256"></td>
					<td class="long"><input type="text" name="descriptions[]" value="<?= $filter->get_description(); ?>" maxlength="256"></td>
					<td class="check"><input type="checkbox" name="hidden_ids[]" value="<?= $filter->get_id(); ?>" <?php if ($filter->is_hidden()) { ?>checked<?php } ?>></td>
				</tr>
				<?php $alt = !$alt; ?>
			<?php } ?>
		</tbody>
		
	</table>
	
	<h4><?php _e('Categories', 'administrate'); ?></h4>
	<table class="<?= $this->plugin->add_namespace(array('seo', 'table'), '-'); ?> wp-list-table widefat" cellspacing="0">
		
		<thead>
			<tr>
				<th class="manage-column">Category</th>
				<th class="manage-column">URL String</th>
				<th class="manage-column">Keywords</th>
				<th class="manage-column">Description</th>
				<th class="check manage-column">Hide</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th class="manage-column">Category</th>
				<th class="manage-column">URL String</th>
				<th class="manage-column">Keywords</th>
				<th class="manage-column">Description</th>
				<th class="check manage-column">Hide</th>
			</tr>
		</tfoot>
		
		<tbody>
			<?php $alt = false; ?>
			<?php foreach ($this->plugin->make_api_call('get_categories', true) as $category) { ?>
				<?php
				//  Attempt to query for the filter
				$filter = new AdministrateCourseFilter($this->plugin, array('filter_object_type'=>'category', 'filter_object_id'=>$category->get_id()));
				
				//  If the filter doesn't exist, create it
				if (!$filter->exists()) {
					$filter->create(array(
						'filter_object_type'	=>	'category',
						'filter_object_id'		=>	$category->get_id(),
						'filter_url_string'		=>	$category->get_name()
					));
				}
				?>
				<input type="hidden" name="ids[]" value="<?= $filter->get_id(); ?>">
				<tr<?php if ($alt) { ?> class="alternate"<?php } ?>>
					<th scope="row"><strong><?= $category->get_name(); ?></strong></th>
					<td class="long"><input type="text" name="url_strings[]" value="<?= $filter->get_url_string(); ?>" maxlength="256"></td>
					<td class="long"><input type="text" name="keywords[]" value="<?= $filter->get_keywords(); ?>" maxlength="256"></td>
					<td class="long"><input type="text" name="descriptions[]" value="<?= $filter->get_description(); ?>" maxlength="256"></td>
					<td class="check"><input type="checkbox" name="hidden_ids[]" value="<?= $filter->get_id(); ?>" <?php if ($filter->is_hidden()) { ?>checked<?php } ?>></td>
				</tr>
				<?php $alt = !$alt; ?>
				<?php foreach ($category->get_subcategories() as $subcategory)  {?>
					<?php
					//  Attempt to query for the filter
					$filter = new AdministrateCourseFilter($this->plugin, array('filter_object_type'=>'subcategory', 'filter_object_id'=>$subcategory->get_id()));
					
					//  If the filter doesn't exist, create it
					if (!$filter->exists()) {
						$filter->create(array(
							'filter_object_type'	=>	'subcategory',
							'filter_object_id'		=>	$subcategory->get_id(),
							'filter_url_string'		=>	$subcategory->get_name()
						));
					}
					?>
					<input type="hidden" name="ids[]" value="<?= $filter->get_id(); ?>">
					<tr<?php if ($alt) { ?> class="alternate"<?php } ?>>
						<th scope="row">&nbsp;&nbsp;&nbsp;&nbsp;<?= $subcategory->get_name(); ?></th>
						<td class="long"><input type="text" name="url_strings[]" value="<?= $filter->get_url_string(); ?>" maxlength="256"></td>
						<td class="long"><input type="text" name="keywords[]" value="<?= $filter->get_keywords(); ?>" maxlength="256"></td>
						<td class="long"><input type="text" name="descriptions[]" value="<?= $filter->get_description(); ?>" maxlength="256"></td>
						<td class="check"><input type="checkbox" name="hidden_ids[]" value="<?= $filter->get_id(); ?>" <?php if ($filter->is_hidden()) { ?>checked<?php } ?>></td>
					</tr>
					<?php $alt = !$alt; ?>
				<?php } ?>
			<?php } ?>
		</tbody>
		
	</table>
	
	<?php submit_button(__('Save Changes', 'administrate'), 'primary', 'save_urls'); ?>
	
</form>
