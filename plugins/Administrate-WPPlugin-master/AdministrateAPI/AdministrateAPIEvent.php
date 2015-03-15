<?php
//  Administrate API Event
class AdministrateAPIEvent extends AdministrateAPIObject {
	
	//  Properties
	protected $fields = array(
		'id'				=>	array(
			'api_field'	=>	'EventID'
		),
		'default_currency'	=>	array(
			'api_field'	=>	'DefaultCurrencyCode'
		),
		'prices'			=>	array(
			'api_field'	=>	'EventPrices'
		),
		'start_date'		=>	array(
			'api_field'	=>	'EventStartDate'
		),
		'end_date'			=>	array(
			'api_field'	=>	'EventEndDate'
		),
		'start_time'		=>	array(
			'api_field'	=>	'StartTime'
		),
		'end_time'			=>	array(
			'api_field'	=>	'EndTime'
		),
		'location'			=>	array(
			'api_field'	=>	'EventLocationName'
		),
		'course_code'		=>	array(
			'api_field'	=>	'CourseCode'
		),
		'course_title'		=>	array(
			'api_field'	=>	'CourseTitle'
		),
		'sold_out'			=>	array(
			'api_field'	=>	'SoldOut'
		),
		'course_categories'	=>	array(
			'api_field'	=>	'CourseCategories'
		),
		'is_provisional'	=>	array(
			'api_field'	=>	'IsProvisional'
		),
		'is_online'			=>	array(
			'api_field'	=>	'IsOnline'
		),
		'region_id'			=>	array(
			'api_field'	=>	'LocationRegionID'
		),
		'max_places'		=>	array(
			'api_field'	=>	'MaxPlaces'
		),
		'num_delegates'	=>	array(
			'api_field'	=>	'Delegates'
		)
	);
	
	//  Get the event's course code
	public function get_course_code() {
		return $this->_get_field('course_code');	
	}
	
	//  Get the event's course title
	public function get_course_title() {
		return $this->_get_field('course_title');
	}
	
	//  Get the event's course
	public function get_course() {
		$this->_set_course();
		return $this->course;	
	}
	
	//  Get the event's course summary
	public function get_course_summary() {
		$this->_set_course();
		return $this->course->get_summary();	
	}
	
	//  Get the event's course schedule
	public function get_course_schedule() {
		$this->_set_course();
		return $this->course->get_schedule();	
	}
	
	//  Get the event's course inclusions
	public function get_course_inclusions() {
		$this->_set_course();
		return $this->course->get_inclusions();	
	}
	
	//  Get the event's course method
	public function get_course_method() {
		$this->_set_course();
		return $this->course->get_method();	
	}
	
	//  Get the event's course prerequisites
	public function get_course_prerequisites() {
		$this->_set_course();
		return $this->course->get_prerequisites();	
	}
	
	//  Get the event's course topics
	public function get_course_topics() {
		$this->_set_course();
		return $this->course->get_topics();	
	}
	
	//  Get the event's course benefits
	public function get_course_benefits() {
		$this->_set_course();
		return $this->course->get_benefits();	
	}
	
	//  Get the event's course duration
	public function get_course_duration() {
		$this->_set_course();
		return $this->course->get_duration();	
	}
	
	//  Get the event's dates
	public function get_dates() {
		return array(
			'start'	=>	$this->get_start_date(),
			'end'	=>	$this->get_end_date()
		);
	}
	
	//  Get the event's start date
	public function get_start_date() {
		return strtotime($this->_get_field('start_date'));
	}
	
	//  Get the event's end date
	public function get_end_date() {
		return strtotime($this->_get_field('end_date'));	
	}

	//  Get the event's times
	public function get_times() {
		return array(
			'start'	=>	$this->get_start_time(),
			'end'	=>	$this->get_end_time()
		);
	}

	// Get the event's start time
	public function get_start_time() {
		return $this->_get_field('start_time');
	}

	// Get the event's end time
	public function get_end_time() {
		return $this->_get_field('end_time');
	}

	//  Whether or not the event is today
	public function is_today() {
		return ($this->get_start_date() == strtotime('today'));	
	}
	
	//  Get the event location
	public function get_location() {
		return $this->_get_field('location');	
	}
	
	//  Get the region ID
	public function get_region_id() {
		return $this->_get_field('region_id');	
	}
	
	//  Get prices
	public function get_prices() {
		if (!property_exists($this, 'prices')) {
			$this->prices = array();
			if ($this->_get_field('prices')) {
				foreach ($this->_get_field('prices') as $price) {

					// We only expose 'normal' PriceLevel for now.
					if($price->PriceLevel == 'Normal') {
						array_push($this->prices, new AdministrateAPIEventPrice($price));
					}
				}
			}
		}
		return $this->prices;
	}
	
	//  Get price by currency
	public function get_price_by_currency($currency) {
		$currencyPrice = false;
		foreach ($this->get_prices() as $price) {
			if ($price->get_currency() == $currency) {
				$currencyPrice = $price;
				break;
			}
		}
		return $currencyPrice;
	}
	
	//  Get default currency
	public function get_default_currency() {
		return $this->_get_field('default_currency');
	}
	
	//  Whether or not the event is sold out
	public function is_sold_out($useNumPlaces = false) {
		if ($useNumPlaces && ($this->get_max_places() !== NULL)) {
			return ($this->_get_field('sold_out') || ($this->get_num_places() === 0));
		} else {
			return $this->_get_field('sold_out');	
		}
	}
	
	//  Get course categories
	public function get_categories() {
		$categories = array();
		$event_categories = $this->_get_field('course_categories');

		/*
		 * Needs to be cast to an array because... Wierdness...
		 * > gettype($event_categories) => 'array'
		 * > is_array($event_categories) => bool(true)
		 * > reset($event_categories) => reset() expects parameter 1 to be array, boolean given
		 * > foreach($event_categories => Warning: Invalid argument supplied for foreach()
		 */
		foreach((array) $event_categories as $category) {
			array_push($categories, new AdministrateAPICourseCategory($category));	
		}
		return $categories;
	}

	//  Get course subcategories
	public function get_subcategories() {
		$subcategories = array();
		/*foreach ($this->_get_field('course_categories') as $category) {
			array_push($categories, new AdministrateAPICourseCategory($category));
		}*/
		return $categories;

	}

	//  Whether or not the event is provisional
	public function is_provisional() {
		return $this->_get_field('is_provisional');	
	}
	
	//  Whether or not the event is online
	public function is_online() {
		return $this->_get_field('is_online');
	}
	
	//  Get max places
	public function get_max_places() {
		return $this->_get_field('max_places');	
	}
	
	//  Get the number of remaining places **NOTE** pass # delegates for much better performance
	public function get_num_places() {
		if (!property_exists($this, 'numPlaces')) {
			if ($this->get_max_places() === NULL) {
				$this->numPlaces = null;
			} else {
				$this->numPlaces = $this->get_max_places() - $this->get_num_delegates();
				if ($this->numPlaces <= 0) {
					$this->numPlaces = 0;
				}
			}
		}
		return $this->numPlaces;	
	}
	
	//  Get the number of delegates
	public function get_num_delegates() {
		return $this->_get_field('num_delegates');
	}
	
	//  Set the course
	private function _set_course() {
		if (!property_exists($this, 'course')) {
			$plugin = AdministratePlugin::get_singleton();
			$this->course = $plugin->make_api_call('get_course_by_code', $this->get_course_code());
		}
	}
	
}
