<?php
//  Administrate event table widget
class AdministrateWidgetEventList extends WPPluginPatternWidget {

	//  Configuration
	protected $key = 'event_list';
	
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
			'show_dates'		=>	true,
			'show_locations'	=>	false,
			'show_codes'		=>	true,
			'num_months'		=>	$this->plugin->get_option('num_months', 'event'),
			'group_by'			=>	'none', // none | location | course | category
			'group_title_pre'	=>	'',
			'group_title_post'	=>	'',
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
		$show_times = $this->plugin->get_option('show_times', 'event');
		
		//  Save event page option
		$registrationPage = $this->plugin->get_option('checkout_page', 'checkout');
		
		//  Add the category filter if supplied
		$eventFilter = array();
		if ($categoryFilter) {
			$eventFilter['CourseCategoryID'] = $categoryFilter;	
		}
		
		//  Loop through events and build groups
		$groups = array();
		if ($group_by == 'none') {
			$groups['default'] = array('title'=>'', 'events'=>array());	
		}
		foreach ($this->get_events($eventFilter) as $event) {
			
			//  Only add the event if it falls within date range
			$dates = $event->get_dates();
			if (
				($this->plugin->get_months_until($dates['start']) <= $num_months) && 
				(!$courseFilter || (strtolower($event->get_course_code()) == $courseFilter)) && 
				(!$locationFilter || ($this->plugin->str_to_key($event->get_location()) == $locationFilter))
			) {
				
				//  If there is no groupings, just add the events to the default group
				if ($group_by == 'none') {
					array_push($groups['default']['events'], $event);
					
				} else {
					
					//  Get unique group titles
					if ($group_by == 'location') {
						$titles = array($event->get_location());
					} else if ($group_by == 'course') {
						$title = '';
						if ($show_codes) {
							$title .= '<span>' . $event->get_course_code() . ':</span> ';	
						}
						$title .= $event->get_course_title();
						$titles = array($title);
					} else if ($group_by == 'category') {
						$titles = array();
						foreach ($event->get_categories() as $category) {
							array_push($titles, $category->get_name());	
						}
					}
					
					//  Loop through titles
					foreach ($titles as $title) {
					
						//  Set the key
						$key = $this->plugin->str_to_key($title);
					
						//  If the group doesn't already exist it, create it
						if (!isset($groups[$key])) {
							$groups[$key] = array(
								'title'		=>	$title,
								'events'	=>	array()
							);	
						}
						
						//  Add the event to the group
						array_push($groups[$key]['events'], $event);
					
					}
					
				}
				
			}
			
		}
		
		//  Sort the groups by key
		ksort($groups);
		
		//  Set the list type
		$listTag = 'dl';
		$itemTag = 'dt';
		$eventLines = 1;
		if ($show_dates && ($group_by != 'course')) {
			++$eventLines;
		}
		if ($show_locations && ($group_by != 'location')) {
			++$eventLines;	
		}
		if ($eventLines == 1) {
			$listTag = 'ul';
			$itemTag = 'li';
		}
		
		//  Include the appropriate template
		ob_start();
		include($this->get_path('/views/event_list.php'));
		return ob_get_clean();
		
	}
	
	//  Get all upcoming events
	public function get_events($filter = array()) {
		if (!property_exists($this, 'events')) {
			$this->events = $this->plugin->make_api_call('get_events', $filter);
		}
		return $this->events;
	}

}
