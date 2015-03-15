<?php extract($params); ?>

<h3><?php _e('Terms &amp; Conditions', 'administrate'); ?></h3>

<?php $this->plugin->display_errors($errors); ?>

<div id="<?= $this->add_namespace('terms', '-'); ?>"><?= $this->get_option('terms'); ?></div>

<label class="<?= $this->add_namespace(array('accept', 'terms'), '-'); ?>"><input type="checkbox" name="tac" value="1"> <?php _e('I have read, understood, and consent to the Terms &amp; Conditions.', 'administrate'); ?></label>

<input type="submit" value="<?php _e('Continue', 'administrate'); ?>" class="<?= $this->plugin->add_namespace('btn', '-'); ?>">
