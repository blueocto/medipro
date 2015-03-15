<form id="<?= $this->plugin->add_namespace(array('cache', 'actions'), '-'); ?>" method="post">
	<h3><?php _e('Cache Actions', 'administrate'); ?></h3>
	<p class="<?= $this->plugin->add_namespace(array('cache', 'action'), '-'); ?>"><?php submit_button(__('Flush Cache', 'administrate'), 'secondary', 'flush_cache', false); ?> <?php _e('Refreshes the values of existing cached API calls.', 'administrate'); ?></p>
	<p class="<?= $this->plugin->add_namespace(array('cache', 'action'), '-'); ?>"><?php submit_button(__('Purge Cache', 'administrate'), 'secondary', 'purge_cache', false); ?> <?php _e('Removes all cached API calls. Only use this if flushing the cache doesn\'t work.', 'administrate'); ?></p>
	<p class="<?= $this->plugin->add_namespace(array('cache', 'action'), '-'); ?>"><?php submit_button(__('Build Cache', 'administrate'), 'secondary', 'build_cache', false); ?> <?php _e('Attempts to pre-cache common API calls. You should only need to do this after purging the cache or upon activating the plugin for the first time.', 'administrate'); ?></p>
	<p class="<?= $this->plugin->add_namespace(array('cache', 'action'), '-'); ?>"><?php submit_button(__('Refresh URLs', 'administrate'), 'secondary', 'refresh_urls', false); ?> <?php _e('Refreshes the course page URLs and caches them for better performance.', 'administrate'); ?></p>
</form>
