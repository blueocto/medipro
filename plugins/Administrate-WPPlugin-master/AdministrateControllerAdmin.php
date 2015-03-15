<?php
//  Administrate plugin admin controller
class AdministrateControllerAdmin extends WPPluginPatternController {
	
	//  Initialize the admin controller
	public function run() {
	
		//  Include admin styles everywhere in the admin so that the icon shows up	
		wp_enqueue_style($this->plugin->add_namespace('admin'), $this->plugin->get_url('/css/admin.css'));
		
		//  Register the settings
		$this->_register_options();
		
		//  Only proceed if we are in the plugin admin
		if ($this->plugin->is_admin()) {
		
			//  Get dependencies
			global $wp_scripts;
			$config = $this->plugin->get_config('plugin');
			$dependencies = $config['dependencies'];
			
			//  Check the PHP version
			$phpVersion = phpversion();
			if (!$this->plugin->is_supported('php', $phpVersion)) {
				$this->plugin->show_error(__('<strong>This website is running an unsupported version of PHP (' . $phpVersion . ').</strong> Please update to version <strong>' . $dependencies['php'] . '</strong> or above.', 'administrate'));
			}
			
			//  Check the jQuery version
			$jqueryVersion = $wp_scripts->registered['jquery']->ver;
			if (!$this->plugin->is_supported('jquery', $jqueryVersion)) {
				$this->plugin->show_error(__('<strong>This website is running an unsupported version of jQuery (' . $jqueryVersion . ').</strong> Please update to version <strong>' . $dependencies['jquery'] . '</strong> or above.', 'administrate'));
			}
			
			//  Check for existence of PHP SOAP client
			if (!class_exists('SoapClient')) {
				$this->plugin->show_error(__('<strong>PHP does not have SOAP support enabled.</strong> Please contact your website host to request that SOAP be enabled in PHP on your website.', 'administrate'));
			}
	
			//  Add the admin CSS & JS
			wp_enqueue_style($this->plugin->add_namespace('common'), $this->plugin->get_url('/css/common.css'));
			
			//  Add additional JS
			wp_enqueue_script($this->plugin->add_namespace('tablesorter'), $this->plugin->get_url('/js/jquery.tablesorter.js'), array('jquery', 'jquery-ui-core'), false, true);
			wp_enqueue_script($this->plugin->add_namespace(array('log', 'table')), $this->plugin->get_url('/js/jquery.administrate_log_table.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-widget', $this->plugin->add_namespace('tablesorter')), false, true);
			wp_enqueue_script($this->plugin->add_namespace('admin'), $this->plugin->get_url('/js/admin.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-widget', $this->plugin->add_namespace('tablesorter'), $this->plugin->add_namespace(array('log', 'table'))), false, true);		
		
			//  If the plugin is in LIVE mode but has no domain, user, or password, show an error
			if ($this->plugin->get_option('mode', 'api') == 'live') {
				$domain = $this->plugin->get_option('domain', 'api');
				$user = $this->plugin->get_option('user', 'api');
				$password = $this->plugin->get_option('password', 'api');
				if (empty($domain) || empty($user) || empty($password)) {
					$this->plugin->show_error('The plugin is set to "live" mode but does not have a domain, user, or password.');
				}
			}
		
			//  If the flush cache action was submitted, flush the cache
			if (isset($_POST['flush_cache'])) {
				$this->plugin->flush_cache(true);
			}
			
			//  If the purge cache action was submitted or the options were updated at all, purge the cache
			if (isset($_POST['purge_cache']) || isset($_GET['settings-updated'])) {
				$this->plugin->purge_cache();
			}
		
			//  If the build cache action was submitted, build the cache
			if (isset($_POST['build_cache'])) {
				$this->plugin->build_cache();
			}
			
			//  If the refresh URLs action was submitted, refresh the URLs
			if (isset($_POST['refresh_urls'])) {
				$this->plugin->refresh_urls();
			}
			
			//  If the save URLs action was submitted, save the URLs
			if (isset($_POST['save_urls'])) {
				$this->plugin->save_urls($_POST);
			}
			
			//  If the test connection action was submitted, test the connection
			if (isset($_POST['test_connection'])) {
				$this->plugin->test_api_connection();
			}
			
			//  If the test performance action was submitted, test the performance
			if (isset($_POST['test_performance'])) {
				$this->plugin->test_api_performance();
			}
			
			//  If the test consistency action was submitted, test the consistency
			if (isset($_POST['test_consistency'])) {
				$this->plugin->test_api_consistency();
			}
					
		}
	
	}
	
}
