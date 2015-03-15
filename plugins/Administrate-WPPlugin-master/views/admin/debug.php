<h3>Debug</h3>

<form id="<?= $this->plugin->add_namespace(array('debug', 'actions'), '-'); ?>" method="post">
	<p class="<?= $this->plugin->add_namespace(array('debug', 'action'), '-'); ?>"><?php submit_button(__('Test Connection', 'administrate'), 'secondary', 'test_connection', false); ?> <?php _e('Test the API connection.', 'administrate'); ?></p>
	<p class="<?= $this->plugin->add_namespace(array('debug', 'action'), '-'); ?>"><?php submit_button(__('Test Performance', 'administrate'), 'secondary', 'test_performance', false); ?> <?php _e('Test the performance of common API calls.', 'administrate'); ?></p>
	<p class="<?= $this->plugin->add_namespace(array('debug', 'action'), '-'); ?>"><?php submit_button(__('Test Consistency', 'administrate'), 'secondary', 'test_consistency', false); ?> <?php _e('Test the consistency of common API calls made 10 times each.', 'administrate'); ?></p>
</form>