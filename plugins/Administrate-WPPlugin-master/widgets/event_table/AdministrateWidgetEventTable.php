<?php
//  Administrate event table widget
class AdministrateWidgetEventTable extends WPPluginPatternWidget {

	//  Configuration
	protected $key = 'event_table';
	protected $showOnce = true;
	
	//  Constructor
	public function __construct(&$plugin) {
		
		//  Call parent constructor
		parent::__construct($plugin);
		
		//  Add the jQuery plugin & CSS
		wp_enqueue_script($this->plugin->add_namespace('tablesorter'), $this->plugin->get_url('/js/jquery.tablesorter.js'), array('jquery'), false, true);
		wp_enqueue_script($this->plugin->add_namespace('tablesorter_pager'), $this->plugin->get_url('/js/jquery.tablesorter.pager.js'), array('jquery', $this->plugin->add_namespace('tablesorter')), false, true);
		wp_enqueue_script($this->add_namespace(''), $this->_get_url('/jquery.administrate_event_table.js'), array('jquery', $this->plugin->add_namespace('tablesorter'), $this->plugin->add_namespace('tablesorter_pager')), false, true);
		wp_enqueue_style($this->add_namespace(''), $this->_get_url('/style.css'));
			
	}
	
	//  Run the checkout
	public function run($params = array()) {
		
		//  Only show if not already shown
		if (!$this->alreadyShown) {
		
			//  Extract parameters
			extract(shortcode_atts(array(
				'show_prices'		=>	$this->plugin->to_boolean($this->plugin->get_option('show_prices', 'event')),
				'num_months'		=>	$this->plugin->get_option('num_months', 'event'),
				'course'			=>	'',
				'show_categories'	=>	true,
				'show_names'		=>	true,
				'show_codes'		=>	true
			), $params));
			
			//  Set param types
			$show_prices = (($show_prices === true) || ($show_prices === 'true'));
			$num_months = intval($num_months);
			$show_categories = (($show_categories === true) || ($show_categories === 'true'));
			$show_names = (($show_names === true) || ($show_names === 'true'));
			$show_codes = (($show_codes === true) || ($show_codes === 'true'));
			$show_times = $this->plugin->get_option('show_times', 'event');
			
			//  Set external pages
			$coursePage = $this->plugin->get_option('course_page', 'course');
			$registrationPage = $this->plugin->get_option('checkout_page', 'checkout');
			
			//  Initialize the event filter
			$eventFilter = array(
				'IncludePrices'	=>	$show_prices
			);
			
			//  If a course code is set, add it to the filter
			if (!empty($course)) {
				$eventFilter['CourseCode'] = $course;	
			}
			
			//  Figure out if a category is set
			$savedCategory = '';
			if (isset($_REQUEST[$this->add_namespace('category')]) && !empty($_REQUEST[$this->add_namespace('category')])) {
				$savedCategory = $_REQUEST[$this->add_namespace('category')];
				$tmpIds = explode(':', $savedCategory);	
				if (intval($tmpIds[1]) === 0) {
					$filterCategory = $tmpIds[0];
				} else {
					$filterCategory = $tmpIds[1];
				}
				$eventFilter['CourseCategoryID'] = intval($filterCategory);
			}
			
			//  Figure out the default number of months
			if (isset($_REQUEST[$this->add_namespace('month')]) && !empty($_REQUEST[$this->add_namespace('month')])) {
				$savedMonths = $_REQUEST[$this->add_namespace('month')];
			} else {
				$savedMonths = $num_months;
				$num_months = 12; // Actually load a full 12 months if JS is enabled so that user can increase timespan without reload
			}
			
			//  Figure out if a location is set
			$savedLocation = '';
			if (isset($_REQUEST[$this->add_namespace('location')]) && !empty($_REQUEST[$this->add_namespace('location')])) {
				$savedLocation = $_REQUEST[$this->add_namespace('location')];
				$eventFilter['EventLocationName'] = $savedLocation;
			}
			
			//  Set whether to show places left
			$show_places = $this->plugin->get_option('show_remaining_places', 'event');
			
			//  Set whether to show sold out events
			$show_sold_out = $this->plugin->get_option('show_sold_out', 'event');
			
			//  Set whether to show today's events
			$show_today = $this->plugin->get_option('show_today', 'event');

			// Pass through any errors given to the widget
			$errors = $params['errors'];

			//  Set the events
			$this->events = $this->plugin->make_api_call('get_events', $eventFilter);
			
			//  Indicate this is alredy shown
			$this->alreadyShown = true;
			
			//  Include the listing templates
			ob_start();
			include($this->get_path('/views/event_table.php'));
			return ob_get_clean();
		
		}
		
	}
	
	//  Get all upcoming events
	public function get_events() {
		return $this->events;
	}

}
