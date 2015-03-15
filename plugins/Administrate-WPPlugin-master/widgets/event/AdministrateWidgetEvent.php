<?php
//  Administrate event widget
class AdministrateWidgetEvent extends WPPluginPatternWidget {

	//  Configuration
	protected $key = 'event';
	
	//  Properties
	private $event = false;
	
	//  Constructor
	public function __construct(&$plugin) {
		
		//  Call parent constructor
		parent::__construct($plugin);
		
		//  Add the CSS
		wp_enqueue_style($this->add_namespace(''), $this->_get_url('/style.css'));
			
	}
	
	//  Run the checkout
	public function run($params = array()) {
		
		//  Extract parameters
		extract(shortcode_atts(array(
			'event'	=>	0
		), $params));
		$eventId = intval($event);
		
		//  If no event parameter was passed, see if we can get a request parameter
		if ($eventId === 0) {
			if (isset($_REQUEST[$this->add_namespace('id')])) {
				$eventId = intval($_REQUEST[$this->add_namespace('id')]);	
			}
		}
		
		//  Try to initialize the event  
		if ($eventId !== 0) {
			$this->event = $this->plugin->make_api_call('get_event', $eventId);
		}
		
		//  If the event exists, display it
		if ($this->event) {
		
			$course_fields = $this->plugin->get_event_fields($this->event, $this->get_option('page_fields'));
		
			//  Save event and registration page options
			$registrationPage = $this->plugin->get_option('checkout_page', 'checkout');
		
			//  Include the template
			ob_start();
			include($this->get_path('/views/event.php'));
			return ob_get_clean();
		
		//  Otherwise display the event table	
		} else {
			
			return $this->plugin->run_widget('event_table');
			
		}
		
	}

}
