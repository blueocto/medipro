<?php if (isset($course_fields) && is_array($course_fields) && !empty($course_fields)) { ?>
	<?php $labels = get_option($this->plugin->add_namespace(array('course', 'options'))); ?>
	
		<?php foreach($course_fields as $field=>$value) { ?>
			<?php if (!empty($value)) { ?>
				<div class="<?= $this->plugin->add_namespace(array('course', 'field', '-')); ?>">
					<h3><?= $labels['course_field_label_'.$field]; ?></h3>
					<?= $this->plugin->filter_text_output($value); ?>
				</div>
			<?php } ?>
		<?php } ?>
	
<?php } ?>
