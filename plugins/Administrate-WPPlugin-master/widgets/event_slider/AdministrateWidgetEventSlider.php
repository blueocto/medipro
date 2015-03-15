<?php
//  Administrate event slider widget
class AdministrateWidgetEventSlider extends WPPluginPatternWidget {

	//  Configuration
	protected $key = 'event_slider';
	
	//  Constructor
	public function __construct(&$plugin) {
		
		//  Call parent constructor
		parent::__construct($plugin);
		
		//  Add the jQuery plugin & CSS
		wp_enqueue_style($this->add_namespace(''), $this->_get_url('/style.css'));
		wp_enqueue_script($this->add_namespace(''), $this->_get_url('/jquery.administrate_event_slider.js'), array('jquery'), false, true);
			
	}
	
	//  Run the checkout
	public function run($params = array()) {
		
		//  Extract parameters
		extract(shortcode_atts(array(
			'show_dates'		=>	true,
			'show_locations'	=>	false,
			'show_codes'		=>	true,
			'items_per_group'	=>	4,
			'num_months'		=>	$this->plugin->get_option('num_months', 'event'),
			'category'			=>	false,
			'course'			=>	false,
			'location'			=>	false
		), $params));
		
		//  Set param types
		$show_dates = (($show_dates === true) || ($show_dates === 'true'));
		$show_locations = (($show_locations === true) || ($show_locations === 'true'));
		$show_codes = (($show_codes === true) || ($show_codes === 'true'));
		$num_months = intval($num_months);
		$categoryFilter = intval($category);
		$courseFilter = strtolower($course);
		$locationFilter = $this->plugin->str_to_key($location);
		$items_per_group = intval($items_per_group);
		$show_times = $this->plugin->get_option('show_times', 'event');
		
		//  Save event page option
		$registrationPage = $this->plugin->get_option('checkout_page', 'checkout');
		
		//  Save whether to treat zero number places as sold out
		$translateNumPlaces = $this->plugin->to_boolean('translate_places_to_status', 'event');
		
		//  Add the category filter if supplied
		$eventFilter = array();
		if ($categoryFilter) {
			$eventFilter['CourseCategoryID'] = $categoryFilter;	
		}
		
		//  Loop through events
		$events = array();
		foreach ($this->get_events($eventFilter) as $event) {
			
			//  Only add the event if it falls within date range
			$dates = $event->get_dates();
			if (
				($this->plugin->get_months_until($dates['start']) <= $num_months) && 
				(!$courseFilter || (strtolower($event->get_course_code()) == $courseFilter)) && 
				(!$locationFilter || ($this->plugin->str_to_key($event->get_location()) == $locationFilter))
			) {
				
				//  Add the event to the array
				array_push($events, $event);
				
			}
			
		}
		
		//  Set whether to show places left
		$show_places = $this->plugin->get_option('show_remaining_places', 'event');
		
		//  Set whether to show sold out events
		$show_sold_out = $this->plugin->get_option('show_sold_out', 'event');
		
		//  Set whether to show today's events
		$show_today = $this->plugin->get_option('show_today', 'event');
		
		//  Include the appropriate template
		ob_start();
		include($this->get_path('/views/event_slider.php'));
		return ob_get_clean();
		
	}
	
	//  Get all upcoming events
	public function get_events($filter = array()) {

		// Add IncludePrices here so that args match on previous caches.
		// We force IncludePrices in AdministrateAPI::get_events() anyway.
		$filter = array_merge(
			array(
				'IncludePrices'		=>	true
			),
			$filter
		);
		if (!property_exists($this, 'events')) {
			$this->events = $this->plugin->make_api_call('get_events', $filter);
		}
		return $this->events;
	}

}
