<?php
//  Include the parent class
require_once(dirname(__FILE__) . '/WPPluginPattern/WPPluginPattern.php');

//  Administrate plugin controller class
class AdministratePlugin extends WPPluginPattern {

	private static $_singleton = null;

	// This version should be incremented if we make changes to the
	// schema in setup_tables();
	private $db_version = 1;

	//  Properties
	protected $namespace = "administrate";
	protected $tables = array(
		
		//  Orders table
		'orders' => array(
			'order_id' => array(
				'type'		=>	'INT',
				'length'	=>	11,
				'null'		=>	false
			),
			'order_status' => array(
				'type'		=>	'ENUM',
				'options'	=>	array('I', 'P', 'C'), // I = incomplete, P = pending (invoiced), C = complete
				'null'		=>	false,
				'default'	=>	'I'
			),
			'order_payment_type' => array(
				'type'		=>	'ENUM',
				'options'	=>	array('I', 'C'), // I = invoice, C = credit card
				'null'		=>	false,
				'default'	=>	'I'
			),
			'order_time_started' => array(
				'type'		=>	'BIGINT',
				'length'	=>	20,
				'null'		=>	false
			),
			'order_time_completed' => array(
				'type'		=>	'BIGINT',
				'length'	=>	20,
				'null'		=>	false,
				'default'	=>	0
			),
			'order_event_id' => array(
				'type'		=>	'INT',
				'length'	=>	11,
				'null'		=>	false
			),
			'order_session_id' => array(
				'type'		=>	'CHAR',
				'length'	=>	32,
				'null'		=>	false
			),
			'order_max_step' => array(
				'type'		=>	'TINYINT',
				'length'	=>	1,
				'null'		=>	false,
				'default'	=>	1
			),
			'order_currency' => array(
				'type'		=>	'CHAR',
				'length'	=>	3,
				'null'		=>	false,
				'default'	=>	'GBP'
			),
			'order_discount' => array(
				'type'		=>	'TEXT',
				'null'		=>	true
			),
			'order_buyer_details' => array(
				'type'		=>	'TEXT',
				'null'		=>	true,
				'serialize'	=>	array(
					'first_name',
					'last_name',
					'company',
					'email',
					'phone',
					'notes'
				)
			),
			'order_invoice_address' => array(
				'type'		=>	'TEXT',
				'null'		=>	true,
				'serialize'	=>	array(
					'address',
					'city',
					'territory',
					'postal_code',
					'country'
				)
			),
			'order_num_attendees' => array(
				'type'		=>	'TINYINT',
				'length'	=>	1,
				'null'		=>	false,
				'default'	=>	1
			),
			'order_attendee_details' => array(
				'type'		=>	'TEXT',
				'null'		=>	true,
				'serialize'	=>	array(
					'first_name',
					'last_name',
					'email',
					'notes'
				),
				'multiple'	=>	true
			),
			'order_processor_transaction_id' => array(
				'type'		=>	'VARCHAR',
				'length'	=>	64,
				'null'		=>	true
			),
			'order_api_invoice_id' => array(
				'type'		=>	'BIGINT',
				'length'	=>	20,
				'null'		=>	false,
				'default'	=>	0
			)
		),
		
		//  Logs table
		'logs' => array(
			'log_id' => array(
				'type'		=>	'BIGINT',
				'length'	=>	20,
				'null'		=>	false
			),
			'log_msg' => array(
				'type'		=>	'TEXT',
				'null'		=>	false
			),
			'log_time' => array(
				'type'		=>	'BIGINT',
				'length'	=>	20,
				'null'		=>	false
			),
			'log_order_id' => array(
				'type'		=>	'INT',
				'length'	=>	11,
				'null'		=>	false,
				'default'	=>	0
			)
		),
		
		//  Cache table
		'caches' => array(
			'cache_id' => array(
				'type'		=>	'BIGINT',
				'length'	=>	20,
				'null'		=>	false
			),
			'cache_method' => array(
				'type'		=>	'VARCHAR',
				'length'	=>	64,
				'null'		=>	false
			),
			'cache_args' => array(
				'type'		=>	'TEXT'
			),
			'cache_result' => array(
				'type'		=>	'MEDIUMTEXT',
				'null'		=>	false
			),
			'cache_time' => array(
				'type'		=>	'BIGINT',
				'length'	=>	20,
				'null'		=>	false
			),
			'cache_expired' => array(
				'type'		=>	'TINYINT',
				'length'	=>	4,
				'null'		=>	false,
				'default'	=>	0
			)
		),
		
		//  Filters table
		'filters' => array(
			'filter_id' => array(
				'type'		=>	'INT',
				'length'	=>	11,
				'null'		=>	false
			),
			'filter_object_type' => array(
				'type'		=>	'VARCHAR',
				'length'	=>	11,
				'null'		=>	false
			),
			'filter_object_id' => array(
				'type'		=>	'VARCHAR',
				'length'	=>	10,
				'null'		=>	false
			),
			'filter_hidden' => array(
				'type'		=>	'TINYINT',
				'length'	=>	4,
				'null'		=>	false,
				'default'	=>	0
			),
			'filter_url_string' => array(
				'type'		=>	'VARCHAR',
				'length'	=>	128,
				'null'		=>	false
			),
			'filter_object_path' => array(
				'type'		=>	'VARCHAR',
				'length'	=>	256
			),
			'filter_object_keywords' => array(
				'type'		=>	'VARCHAR',
				'length'	=>	256
			),
			'filter_object_description' => array(
				'type'		=>	'VARCHAR',
				'length'	=>	256
			)
		)
		
	);
	protected $oldTables = array(
		'sessions'	=>	'administrateSession',
		'orders'	=>	'administrateOrders',
		'logs'		=>	'administrateLogs'
	);
	protected $subdomainPlaceholder = '<subdomain>';
	protected $domainPlaceholder = '<domain>';
	protected $cacheFlusherLaunched = false;
	
	//  Constructor
	public function __construct($config, &$db, $debug = false) {
		
		//  Call parent init
		parent::__construct($config, $db, $debug);
		
		$this->debug = $this->to_boolean($this->get_option('debug', 'api'));
		if (!$this->debug) {
			unset($this->config['pages']['debug']);		
		}
		
		//  Set the cache
		$this->cacheTime = intval($this->get_option('cache_timeout', 'api'));
		if ($this->cacheTime) {
			require_once($this->get_path('/AdministrateCache.php'));
		}
		
		//  Load and initialize the API
		require_once($this->get_path('/AdministrateAPI/AdministrateAPI.php'));
		$this->init_api();
		
		//  If running in the admin, include and initialize admin controller
		if (is_admin()) {

			//  Activate just in case
			if ($this->is_admin()) {
				$this->activate();
			}
			
			//  Initialize the admin controller
			require_once($this->get_path('/AdministrateControllerAdmin.php'));
			$this->controller = new AdministrateControllerAdmin($this);	
		
		//  Or else include and initialize the public controller
		} else {
			require_once($this->get_path('/AdministrateControllerPublic.php'));
			$this->controller = new AdministrateControllerPublic($this);
		}
		self::$_singleton = $this;
	}
	
	public static function get_singleton() {
		return self::$_singleton;
	}

	public function setup_tables() {

		// Default this option to 0 if it doesn't exist
		if(get_option('adm_db_version') == false) {
			add_option('adm_db_version', 0);
		}

		// If the version we specify doesn't match the saved version, updates are ready.
		// Documentation for this method:
		//     http://codex.wordpress.org/Creating_Tables_with_Plugins#Creating_or_Updating_the_Table

		if($this->db_version != get_option('adm_db_version')) {

			require_once(ABSPATH.'wp-admin/includes/upgrade.php');

			$sql = "CREATE TABLE wp_administrate_caches (
						cache_id bigint(20) NOT NULL AUTO_INCREMENT,
						cache_method varchar(64) NOT NULL,
						cache_args text,
						cache_result mediumtext NOT NULL,
						cache_time bigint(20) NOT NULL,
						cache_expired tinyint(4) NOT NULL DEFAULT '0',
						PRIMARY KEY  (cache_id)
					);";

			// Check the new schema against the present one and migrate.
			dbDelta($sql);

			$sql = "CREATE TABLE wp_administrate_filters (
						filter_id int(11) NOT NULL AUTO_INCREMENT,
						filter_object_type varchar(11) NOT NULL,
						filter_object_id varchar(10) NOT NULL,
						filter_hidden tinyint(4) NOT NULL DEFAULT '0',
						filter_url_string varchar(128) NOT NULL,
						filter_object_path varchar(256) DEFAULT NULL,
						filter_object_keywords varchar(256) DEFAULT NULL,
						filter_object_description varchar(256) DEFAULT NULL,
						PRIMARY KEY  (filter_id)
					);";

			// Check the new schema against the present one and migrate.
			dbDelta($sql);

			$sql = "CREATE TABLE wp_administrate_logs (
						log_id bigint(20) NOT NULL AUTO_INCREMENT,
						log_msg text NOT NULL,
						log_time bigint(20) NOT NULL,
						log_order_id int(11) NOT NULL DEFAULT '0',
						PRIMARY KEY  (log_id)
					)";

			// Check the new schema against the present one and migrate.
			dbDelta($sql);

			$sql = "CREATE TABLE wp_administrate_orders (
						order_id int(11) NOT NULL AUTO_INCREMENT,
						order_status enum('I','P','C') NOT NULL DEFAULT 'I',
						order_payment_type enum('I','C') NOT NULL DEFAULT 'I',
						order_time_started bigint(20) NOT NULL,
						order_time_completed bigint(20) NOT NULL DEFAULT '0',
						order_event_id int(11) NOT NULL,
						order_session_id char(32) NOT NULL,
						order_max_step tinyint(1) NOT NULL DEFAULT '1',
						order_currency char(3) NOT NULL DEFAULT 'GBP',
						order_discount text,
						order_buyer_details text,
						order_invoice_address text,
						order_num_attendees tinyint(1) NOT NULL DEFAULT '1',
						order_attendee_details text,
						order_processor_transaction_id varchar(64) DEFAULT NULL,
						order_api_invoice_id bigint(20) NOT NULL DEFAULT '0',
						order_being_processed int(1) NOT NULL DEFAULT '0',
						PRIMARY KEY (order_id)
					)";

			// Check the new schema against the present one and migrate.
			dbDelta($sql);

			update_option('adm_db_version', $this->db_version);
		}
	}

	//  Display the price widget
	public function display_price_widget(&$event, $fieldName, $defaultCurrency = '', $showCurrencyIndicator = true, $currencyIndicator = 'symbol') {
		$this->controller->display_price_widget($event, $fieldName, $defaultCurrency, $showCurrencyIndicator);
	}
	
	//  Display the currency selector
	public function display_currency_selector(&$events, $defaultCurrency = '', $fieldName = '') {
		$this->controller->display_currency_selector($events, $defaultCurrency, $fieldName);	
	}
	
	//  Display the currency prices
	public function display_currency_prices(&$event, $pricingBasis = false, $defaultCurrency = '', $showCurrencyIndicator = true, $currencyIndicator = 'symbol') {
		$this->controller->display_currency_prices($event, $pricingBasis, $defaultCurrency, $showCurrencyIndicator, $currencyIndicator);
	}
	
	//  Get currency symbol
	public function get_currency_symbol($currency) {
		return $this->controller->get_currency_symbol($currency);	
	}
	
	//  Format a currency
	public function format_currency($amount, $currency, $showCurrencyIndicator = true, $currencyIndicator = 'symbol') {
		return $this->controller->format_currency($amount, $currency, $showCurrencyIndicator, $currencyIndicator);	
	}
	
	//  Format a datetime
	public function format_datetime($ts) {
		return date_i18n('M j @ g:ia', $ts);
	}
	
	//  Format a date 
	public function format_date($ts, $includeMonth = true, $includeYear = false) {
		$format = '';
		if ($includeMonth) {
			$format .= 'M ';	
		}
		$format .= 'j';
		if ($includeYear) {
			$format .= ' Y';	
		}
		return date_i18n($format, $ts);
	}
	
	//  Format a date span
	public function format_date_span($dates = false) {
		
		//  If no dates were passed, use the current time
		if (!$dates) {
			$dates = array(
				'start'	=>	time(),
				'end'	=>	time()
			);
		}
		
		//  Figure out whether to include the year
		$includeYear = (intval($this->get_option('show_year', 'event')) === 1);
		
		//  Initialize the string
		$str = '';
		
		//  If the start date and end date aren't the same, show the start date
		if ($dates['start'] != $dates['end']) {
			$str .= $this->format_date(
				$dates['start'], 
				true, 
				($includeYear && (date_i18n('Y', $dates['start']) != date_i18n('Y', $dates['end'])))
			);
			$str .= __(' - ', 'administrate');	
		}
		
		//  Add the end date to the date span
		$str .= $this->format_date(
			$dates['end'], 
			($dates['start'] == $dates['end']) || (date_i18n('M', $dates['start']) != date_i18n('M', $dates['end'])), 
			$includeYear
		);
		
		//  Return the date span
		return $str;
		
	}

	// $times is an array in the following format
	// array (
	//   'start' => 'hh:mm:ss',
	//   'end' => 'hh:mm:ss'
	// )
	public function format_time_span($times, $separator = '-') {
		return substr($times['start'], 0, 5) . ' ' . $separator . ' ' . substr($times['end'], 0, 5);
	}

	//  Get number of months until event
	public function get_months_until($ts) {
		$currentTime = time();
		$i = 1;
		while (($currentTime = strtotime("+1 MONTH", $currentTime)) <= $ts) {
    		++$i;
		}
		return $i;
	}
	
	//  Get the registration URL of an event
	public function get_registration_url($eventId) {
		$url = get_permalink($this->get_option('checkout_page', 'checkout'));
		if (strpos($url, '?') > -1) {
			$url .= '&';	
		} else {
			$url .= '?';
		}
		$url .= $this->add_namespace(array('checkout', 'event', 'id')) . '=' . $eventId;
		// $url is now a complete URL - began with get_permalink
		return $url;
	}
	
	//  Get the course url structure
	public function get_course_url_structure() {
		if (!property_exists($this, 'courseUrlStructure')) {
			$this->courseUrlStructure = $this->get_option('course_url_structure', 'course');
		}
		return $this->courseUrlStructure;	
	}
	
	//  Get the course URL structure parts
	public function get_course_url_parts() {
		if (!property_exists($this, 'courseUrlParts')) {
			$this->courseUrlParts = explode('-', $this->get_course_url_structure());
		}
		return $this->courseUrlParts;
	}
	
	//  Get the course page
	public function get_course_page() {
		if (!property_exists($this, 'coursePage')) {
			$this->coursePage = $this->get_option('course_page', 'course');
		}
		return $this->coursePage;
	}
	
	//  Get the course page URL
	public function get_course_page_base_url() {
		if (!property_exists($this, 'coursePageBaseUrl')) {

			// Detect presence of http/https. get_permalink always uses url as defined
			// in settings, however, home_url() will return url being used (if overridden
			// by .htaccess for example)
			if(substr(get_permalink($this->get_course_page()), 0, 5) == 'https') {
				$site_url = home_url('', 'https');
			} else {
				$site_url = home_url('', 'http');
			}
			$this->coursePageBaseUrl = substr(get_permalink($this->get_course_page()), strlen($site_url));
		}
		return $this->coursePageBaseUrl;
	}
	
	//  Whether or not to use pretty course URLs
	public function use_custom_course_urls() {
		if (!property_exists($this, 'customCourseUrls')) {
			$this->customCourseUrls = ((strlen($this->get_course_url_structure()) > 0) && (strlen(get_option('permalink_structure')) > 0));
		}
		return $this->customCourseUrls;	
	}
	
	//  Get the info URL of a course
	public function get_course_url($course, $subcategory = false, $category = false, $forceGenerate = false) {
		
		//  If there is a custom structure, generate it
		if ($this->use_custom_course_urls()) {
		
			//  If the course object wasn't passed in, query for it
			if (!is_object($course)) {
				$course = $this->plugin->make_api_call('get_course_by_code', $course);
			}
		
			//  See if there is already a saved filter path
			require_once($this->get_path('/AdministrateCourseFilter.php'));
			$filter = new AdministrateCourseFilter(
				$this, 
				array(
					'filter_object_type'	=>	'course',
					'filter_object_id'		=>	$course->get_code(),
					'filter_hidden'			=>	0
				)
			);
			
			//  If the filter exists, return the cached path
			if ($filter->exists() && !$forceGenerate) {
				// return the full URL built from the filter
				return home_url($filter->get_object_path());
				
			//  Otherwise continue to generate it
			} else {
			
				//  Generate the URL
				$params = array();
				if ($category) {
					$params['category'] = $category;
				} else if (isset($_REQUEST[$this->add_namespace(array('category', 'id'))])) {
					$params['category'] = AdministrateAPI::get_category($_REQUEST[$this->add_namespace(array('category', 'id'))]);
				} else {
					$categories = $course->get_categories();
					if (count($categories) > 0) {
						$params['category'] = $categories[0];
					}
				}
				if ($subcategory) {
					$params['subcategory'] = $subcategory;
				} else if (isset($_REQUEST[$this->add_namespace(array('subcategory', 'id'))])) {
					$params['subcategory'] = AdministrateAPI::get_subcategory($_REQUEST[$this->add_namespace(array('subcategory', 'id'))]);
				} else if (isset($params['category'])) {
					$subcategories = $params['category']->get_subcategories();
					if (count($subcategories) > 0) {
						$params['subcategory'] = $subcategories[0];	
					}
				}
				$params['course'] = $course;
				// get_course_page_url returns a full URL
				return $this->get_course_page_url($params);
			
			}
		
		//  Otherwise just pass the course ID
		} else {
			// get_course_page_url returns a full URL
			return $this->get_course_page_url(array('course'=>$course));
		}
	
	}
	
	//  Get the info URL of a subcategory
	public function get_subcategory_url($subcategory, $category = false, $forceGenerate = false) {
		
		//  If there is a custom structure, generate it
		if ($this->use_custom_course_urls()) {
		
			//  If the subcategory object wasn't passed in, query for it
			if (!is_object($subcategory)) {
				$subcategory = AdministrateAPI::get_subcategory($subcategory);		
			}
			
			//  See if there is already a saved filter path
			require_once($this->get_path('/AdministrateCourseFilter.php'));
			$filter = new AdministrateCourseFilter(
				$this, 
				array(
					'filter_object_type'	=>	'subcategory',
					'filter_object_id'		=>	$subcategory->get_id(),
					'filter_hidden'			=>	0
				)
			);
			
			//  If the filter exists, return the cached path
			if ($filter->exists() && !$forceGenerate) {
				// return the full URL built from the filter
				return home_url($filter->get_object_path());
				
			//  Otherwise continue to generate it
			} else {
				
				//  Set the params
				$params = array();
				if ($category) {
					$params['category'] = $category;
				} else if (isset($_REQUEST[$this->add_namespace(array('category', 'id'))])) {
					$params['category'] = AdministrateAPI::get_category($_REQUEST[$this->add_namespace(array('category', 'id'))]);
				} else {
					$params['category']	= $subcategory->get_parent_category();
				}
				$params['subcategory'] = $subcategory;
				
				//  Return the subcategory URL
				return $this->get_course_page_url($params);

			}
		
		//  Otherwise just pass the course ID
		} else {
			return $this->get_course_page_url(array('subcategory'=>$subcategory));
		}
	
	}
	
	//  Get the info URL of a category
	public function get_category_url($category, $forceGenerate = false) {
	
		//  If there is a custom structure, generate it
		if ($this->use_custom_course_urls()) {
			
			//  If the category isn't an object, query for it
			if (!is_object($category)) {
				$category = AdministrateAPI::get_category($category);		
			}
		
			//  See if there is already a saved filter path
			require_once($this->get_path('/AdministrateCourseFilter.php'));
			$filter = new AdministrateCourseFilter(
				$this, 
				array(
					'filter_object_type'	=>	'category',
					'filter_object_id'		=>	$category->get_id(),
					'filter_hidden'			=>	0
				)
			);
			
			//  If the filter exists, return the cached path
			if ($filter->exists() && !$forceGenerate) {
				// return the full URL built from the filter
				return home_url($filter->get_object_path());
			
			//  Or else generate the URL
			} else {
				return $this->get_course_page_url(array('category'=>$category));
			}
		
		//  Or else just return the normal URL
		} else {
			return $this->get_course_page_url(array('category'=>$category));	
		}
	
	}
	
	//  Get a course page URL
	public function get_course_page_url($params = array()) {
		
		//  Set the base URL
		$url = $this->get_course_page_base_url();
		
		//  If there is a custom structure, generate it
		if ($this->use_custom_course_urls()) {
		
			//  Include the course filter class
			require_once($this->get_path('/AdministrateCourseFilter.php'));
			
			//  Split the structure into parts
			$urlStructureParts = $this->get_course_url_parts();
			
			//  Loop through URL structure backwards to find the first identifier available
			$objectSuffix = '';
			for ($i = count($urlStructureParts)-1; $i >= 0; --$i) {	
				
				//  If the part matches the passed key, generate the URL from here
				if (isset($params[$urlStructureParts[$i]])) {
					
					//  Get the associated filter
					if ($urlStructureParts[$i] == 'course') {
						$objectId = $params[$urlStructureParts[$i]]->get_code();
					} else {
						$objectId = $params[$urlStructureParts[$i]]->get_id();
					}
					$filter = new AdministrateCourseFilter(
						$this, 
						array(
							'filter_object_type'	=>	$urlStructureParts[$i], 
							'filter_object_id'		=>	$objectId, 
							'filter_hidden'			=>	0
						)
					); 
					
					if ($filter->exists()) {
						$objectSuffix = $filter->get_url_string() . '/' . $objectSuffix;
					}
					
				} 
				
			}

			// Add the suffix to the URL.
			// Dependant on config, $url may or may not have a trailing slash at this point, so we make sure it does.
			if(substr($url, -1) != '/') {
				$url .= '/';
			}

			$url .= $objectSuffix;
		
		//  Otherwise just add the query string
		} else {
			
			//  Add the appropriate query string prefix
			if (strpos($url, '?') > -1) {
				$url .= '&';	
			} else {
				$url .= '?';
			}
			
			//  Loop through params and add them to the query string		
			foreach ($params as $key=>$val) {
				if ($key == 'course') {
					$objectId = $params[$key]->get_code();
				} else {
					$objectId = $params[$key]->get_id();
				}
				$url .= $this->add_namespace(array($key, 'id')) . '=' . $objectId;
			}
		
		}
		
		// Return the full site url for the relative URL we have generated
		return home_url($url);
		
	}
	
	//  Whether or not the filter object is supposed to be hidden
	public function filter_object_is_hidden($objectType, $objectId) {
		
		//  Include the course filter class
		require_once($this->get_path('/AdministrateCourseFilter.php'));
		
		//  Get the associated filter
		$filter = new AdministrateCourseFilter(
			$this, 
			array(
				'filter_object_type'	=>	$objectType, 
				'filter_object_id'		=>	$objectId, 
				'filter_hidden'			=>	1
			)
		); 
		
		//  Return a boolean
		return $filter->exists();
		
	}
	
	//  Get event field values based on passed list (array) or field names
	public function get_event_fields(&$event, $fields) {
		
		//  If an array of fields wasn't passed, try unserializing it
		if (!is_array($fields)) {
			$fields = unserialize($fields);	
		}
		if (empty($fields)) {
			$fields = array();	
		}
		
		//  Add the requested fields to the values  
		$values = array();
		if (in_array('code', $fields)) {
			$values['code'] = $event->get_course_code();	
		}
		if (in_array('summary', $fields)) {
			$values['summary'] = $event->get_course_summary();	
		}
		if (in_array('schedule', $fields)) {
			$values['schedule'] = $event->get_course_schedule();	
		}
		if (in_array('location', $fields)) {
			$values['location'] = $event->get_location();	
		}
		if (in_array('inclusions', $fields)) {
			$values['inclusions'] = $event->get_course_inclusions();	
		}
		if (in_array('method', $fields)) {
			$values['method'] = $event->get_course_method();	
		}
		if (in_array('prerequisites', $fields)) {
			$values['prerequisites'] = $event->get_course_prerequisites();	
		}
		if (in_array('topics', $fields)) {
			$values['topics'] = $event->get_course_topics();	
		}
		if (in_array('benefits', $fields)) {
			$values['benefits'] = $event->get_course_benefits();	
		}
		if (in_array('duration', $fields)) {
			$values['duration'] = $event->get_course_duration();	
		}
		
		//  Return the values
		return $values;
		
	}
	
	//  Get course field values based on passed list (array) or field names
	public function get_course_fields(&$course, $fields) {
		
		//  If an array of fields wasn't passed, try unserializing it
		if (!is_array($fields)) {
			$fields = unserialize($fields);	
		}
		if (empty($fields)) {
			$fields = array();	
		}
		
		//  Add the requested fields to the values  
		$values = array();
		if (in_array('code', $fields)) {
			$values['code'] = $course->get_code();	
		}
		if (in_array('summary', $fields)) {
			$values['summary'] = $course->get_summary();	
		}
		if (in_array('schedule', $fields)) {
			$values['schedule'] = $course->get_schedule();	
		}
		if (in_array('inclusions', $fields)) {
			$values['inclusions'] = $course->get_inclusions();	
		}
		if (in_array('method', $fields)) {
			$values['method'] = $course->get_method();	
		}
		if (in_array('prerequisites', $fields)) {
			$values['prerequisites'] = $course->get_prerequisites();	
		}
		if (in_array('topics', $fields)) {
			$values['topics'] = $course->get_topics();	
		}
		if (in_array('benefits', $fields)) {
			$values['benefits'] = $course->get_benefits();	
		}
		if (in_array('duration', $fields)) {
			$values['duration'] = $course->get_duration();	
		}
		
		//  Return the values
		return $values;
		
	}

	/**
	 * @param bool $where - false or an array of 'where' clause, e.g. 'order_id' => 4
	 * @param int $start - start results at N'th value - default 0
	 * @param int $limit - show only X results - defaults to MAX_INT (all results)
	 * @return array - array of AdministrateWidgetCheckoutOrder objects
	 */
	public function get_orders($where = false, $start = 0, $limit = PHP_INT_MAX) {

		// Always sort orders by time started
		$order = array('order_time_started' => 'DESC');
		require_once($this->get_path('/widgets/checkout/AdministrateWidgetCheckoutOrder.php'));
		$dao = new AdministrateWidgetCheckoutOrder($this);
		return $dao->get_all($where, $order, $start, $limit);
	}

	/**
	 * @param bool $where - false or an array of 'where' clause, e.g. 'order_id' => 4
	 * @return int - number of items matching this condition
	 */
	public function count_orders($where = false) {
		if(!$where) {

			// No where specified - return all orders
			$sql = "SELECT COUNT(order_id) AS Count FROM " . $this->get_orders_table();
		} else {

			// Where specified - build parameterized query string
			$where_string = " WHERE ";
			$where_items = array();
			foreach($where as $field => $value) {
				$where_items[] = $field . ' = %s';
				$params[] = $value;
			}
			$where_string .= implode(' AND ', $where_items);

			// Prepare the statement
			$sql = $this->db->prepare(
				"SELECT COUNT(order_id) AS Count FROM " . $this->get_orders_table() . $where_string,
				$params
			);
		}

		$count = $this->db->get_row($sql, 'ARRAY_A');

		return $count['Count'];
	}

	/**
	 * @return array - list of event ID's which an order exists for
	 */
	public function get_ordered_event_ids() {
		// No need to paramaterize - get_orders_table is specified
		// in code - user's can't determine this.
		$sql = "SELECT DISTINCT(order_event_id) FROM " . $this->get_orders_table();
		return $this->db->get_results($sql, 'ARRAY_A');
	}

	//  Get the number of places available for an event
	public function get_event_num_places(&$event) {
		return $this->make_api_call('get_event_num_places', $event);
	}
	
	//  Get API URL
	public function get_api_url($mode, $domain) {
		$serviceConfig = $this->get_config('service');
		//return str_replace($this->subdomainPlaceholder, $subdomain, $serviceConfig['urls'][$mode]);
		return str_replace($this->domainPlaceholder, $domain, $serviceConfig['urls'][$mode]);
	}

	// We use this to cache individual API calls on a per-pageload
	// basis. This improves (for example) the event listing, because
	// we don't have to do a round-trip to the database for every
	// single event to look up it's code/url. (e.g. when you have 10 events
	// for the same course).
	private $plugin_internal_cache = array();

	//  Make an API call
	public function make_api_call($method, $raw_args = null, $noCache = false) {
		$result = null;

		//  Sort the arguments in case the order matters
		if (is_array($raw_args)) {
			ksort($raw_args);
		}

		//  At this point, ensure we have a serialised copy of the args
		$encoded_args = AdministratePlugin::_encode($raw_args);

		//  If the cache time is greater than zero and nocache wasn't specified ...
		if ($this->cacheTime && !$noCache) {

			// If we've already looked this up, don't
			// hit the DB a second time.
			if(array_key_exists($method.$encoded_args, $this->plugin_internal_cache)) {
				$result = $this->plugin_internal_cache[$method.$encoded_args];
			} else {
				//  Query the database for the latest cache result that matches the method and argument
				$row = $this->db->get_row(
					"SELECT
						*
					FROM
						" . $this->get_caches_table() . "
					WHERE
						cache_method = '" . $method . "'
						AND cache_args = '" . $encoded_args . "'
					ORDER BY
						cache_time DESC"
				);

				//  If the row exists, use the result
				if ($row) {
					//  Set the result
					$result = $this->_decode($row->cache_result);
					$this->plugin_internal_cache[$method.$encoded_args] = $result;

					//  Log the API cache
					$this->log('API Cache (from ' . date('n/j/Y g:ia', $row->cache_time) . '): ' . $method);

					//  Flag any old caches with the same parameters but older than the cache time
					if ($row->cache_time < (time() - $this->cacheTime)) {
						$this->db->query(
							"UPDATE
								" . $this->get_caches_table() . "
							SET
								cache_expired = 1
							WHERE
								cache_method = '" . $method . "'
								AND cache_args = '" . $encoded_args . "'"
						);

						if (!$this->cacheFlusherLaunched) {
							add_action('wp_footer', array($this, 'launch_cache_flusher'));
							$this->cacheFlusherLaunched = true;
						}
					}
				}
			}
		}

		//  Cache miss - make API call.
		if ($result === null) {
			$result = AdministrateAPI::$method($raw_args);

			// And cache it...
			if ($result !== null && $this->cacheTime && !$noCache) {
				$cache = new AdministrateCache($this);
				$cache->create($method, $encoded_args, $this->_encode($result));
			}
		}

		//  Return the result
		return $result;
	}

	/**
	 * @param $event_id - Event ID to be updated
	 * @param $event - AdministrateAPIEvent object to replace it with
	 */
	public function update_individual_event($event_id, $event) {

		// Fetch all get_events calls from db
		$cached_events = $this->db->get_results(
			"SELECT *
			FROM
				" . $this->get_caches_table() . "
			WHERE
				cache_method = 'get_events'
			",
			'ARRAY_A'
		);

		// foreach 'saved' getEvents call
		foreach($cached_events as $cached_event) {

			// decode the result - it's an array of event objects
			$results = $this->_decode($cached_event['cache_result']);

			// foreach event object in the array
			foreach($results as $key => $result) {

				// if it's the one we need, replace it with out event
				if($result->get_id() == $event_id) {
					$results[$key] = $event;
				}
			}

			// rewrite the new value to the database
			$this->db->query(
				"UPDATE
					" . $this->get_caches_table() . "
				SET
					cache_result = '" . $this->_encode($results) . "'
				WHERE
					cache_id = " . $cached_event['cache_id']
			);
		}

	}

	//  Initialize the API
	public function init_api($force = false) {
		if (!AdministrateAPI::$initialized || $force) {
			$serviceConfig = $this->get_config('service');
			$apiOptions = get_option($this->add_namespace('api_options'));
			if ($apiOptions['mode'] == 'demo') {
				$url = $serviceConfig['urls']['demo'];
				$user = $serviceConfig['demoUser'];
				$password = $serviceConfig['demoPassword'];
			} else {
				//$url = $this->get_api_url($apiOptions['mode'], $apiOptions['subdomain']);
				$url = $this->get_api_url($apiOptions['mode'], $apiOptions['domain']);
				$user = $apiOptions['user'];
				$password = $apiOptions['password'];
			}
			AdministrateAPI::init($url, $user, $password, $this->debug);
		}
	}
	
	//  Purge the cache
	public function purge_cache() {
		$this->db->query("DELETE FROM " . $this->get_caches_table());
		if (isset($_POST['purge_cache'])) {
			$this->show_notice(__('You have successfully purged the cache.', 'administrate'));
		}
	}
	
	//  Build the cache
	public function build_cache() {
		
		//  Increase execution time limit
		set_time_limit(1800);
		
		//  Get unique categories
		$categories = $this->make_api_call('get_categories');
		
		//  Loop through categories
		foreach ($categories as $category) {
				
			//  Make the category call
			$category = $this->make_api_call('get_category', $category->get_id());
			
			//  Get the subcategories
			$subcategories = $category->get_subcategories();
					
			//  Loop through subcategories
			foreach ($subcategories as $subcategory) {
				$subcategory = $this->make_api_call('get_subcategory', $subcategory->get_id());
			}

		}
		
		//  Get all courses
		$courses = $this->make_api_call('get_courses');
		
		//  Get all locations
		$locations = $this->make_api_call('get_locations');
		
		//  Get all events in the next 12 months
		$events = $this->make_api_call('get_events');
		
		//  Loop through events
		$courseCodes = array();
		foreach ($events as $event) {
			
			//  Make the event call
			$event = $this->make_api_call('get_event', $event->get_id());
			
			//  If we haven't queried for the course by code already, do it now
			if (!in_array($event->get_course_code(), $courseCodes)) {
				$course = $this->make_api_call('get_course_by_code', $event->get_course_code());	
			}
			
		}
		
		$this->show_notice(__('You have successfully built the cache.', 'administrate'));
	
	}
	
	//  Flush the cache (re-cache any items flagged as expired)
	public function flush_cache() {
		
		//  Increase execution time limit
		set_time_limit(1800);
		
		//  Query the database for caches that have expired
		$caches = $this->db->get_results("SELECT * FROM " . $this->get_caches_table() . " WHERE cache_expired = 1 ORDER BY cache_time ASC");
		
		//  Loop through caches and re-cache them
		foreach ($caches as $cache) {
			
			//  Set methods & arguments
			$method = $cache->cache_method;
			$args = $this->_decode($cache->cache_args);

			//  Get the result from the API
			$result = AdministrateAPI::$method($args);

			$old_count = count($this->_decode($cache->cache_result));
			$new_count = count($result);

			// Default to 1, we're going to set it if we have a problem
			$variance = 1;
			if($new_count == 0 && $old_count > 0) {

				// Our new result had no rows, the previous
				// result *did* have rows - set 999 to ensure
				// caught for mail. Can induce false-positives.
				$variance = 999;
			} elseif($new_count > 0 && $old_count > 0) {

				// Both non-zero - calculate variance.
				$variance = $old_count / $new_count;
			}

			// If this has changed by more than 20% either way - we should check this.
			if(($variance > 1.2 || $variance < 0.8) && $variance != 0) {
				mail(
					'support@getadministrate.com',
					'Check - Plugin Cache Error',
						"Website: " . $_SERVER['SERVER_NAME'] .
						"\nMethod: " . $method .
						"\nArgs: " . $cache->cache_args .
						"\nOld Count: " . $old_count .
						"\nNew Count: " . $new_count .
						"\nVariance: " . $variance
				);
			}

			$this->db->query("DELETE FROM " . $this->get_caches_table() . " WHERE cache_method = '" . $cache->cache_method . "' AND cache_args = '" . $cache->cache_args . "'");
			$cacheDao = new AdministrateCache($this);
			$cacheDao->create($cache->cache_method, $cache->cache_args, $this->_encode($result));

		}
		
		$this->show_notice(__('You have successfully flushed the cache.', 'administrate'));
		
	}
	
	//  Launch cache flusher via AJAX
	public function launch_cache_flusher() {
		?>
		<script>
		administrateCacheFlusher = '<?= wp_login_url(); ?>';
		</script>
		<?php	
	}
	
	//  Refresh course page URLs
	public function refresh_urls() {
		
		//  Only proceed if there is a custom URL structure defined
		if ($this->use_custom_course_urls()) {
		
			//  Increase execution time limit
			set_time_limit(1800);
			
			//  Include the course filter object
			require_once($this->get_path('/AdministrateCourseFilter.php'));
			
			//  Loop through categories
			foreach ($this->make_api_call('get_categories', true) as $category) {
					
				//  Attempt to query for the filter
				$filter = new AdministrateCourseFilter(
					$this, 
					array(
						'filter_object_type'	=>	'category', 
						'filter_object_id'		=>	$category->get_id()
				));
				
				//  If the filter doesn't exist, create it
				if (!$filter->exists()) {
					$filter->create(array(
						'filter_object_type'	=>	'category',
						'filter_object_id'		=>	$category->get_id(),
						'filter_url_string'		=>	$category->get_name()
					));
				}

				// Get full URL for this category
				$full_category_url = $this->get_category_url($category, true);

				// We save only the relative part of the URL - when we render it
				// we'll calculate the full URL including hostname.
				$relative_category_url = substr($full_category_url, strlen(home_url()));

				$filter->save(array(
					'filter_object_path'	=>	$relative_category_url
				));
				
				//  Loop through subcategories
				foreach ($category->get_subcategories() as $subcategory)  {
						
					//  Attempt to query for the filter
					$filter = new AdministrateCourseFilter(
						$this, 
						array(
							'filter_object_type'	=>	'subcategory', 
							'filter_object_id'		=>	$subcategory->get_id()
						)
					);
					
					//  If the filter doesn't exist, create it
					if (!$filter->exists()) {
						$filter->create(array(
							'filter_object_type'	=>	'subcategory',
							'filter_object_id'		=>	$subcategory->get_id(),
							'filter_url_string'		=>	$subcategory->get_name()
						));
					}

					// Get full URL for this subcategory
					$full_subcategory_url = $this->get_subcategory_url($subcategory, $category, true);

					// We save only the relative part of the URL - when we render it
					// we'll calculate the full URL including hostname.
					$relative_subcategory_url = substr($full_subcategory_url, strlen(home_url()));
					$filter->save(array(
						'filter_object_path'	=>	$relative_subcategory_url
					));
				
				}
				
			}
			
			//  Loop through courses
			foreach ($this->make_api_call('get_courses') as $course) {
					
				//  Attempt to query for the filter
				$filter = new AdministrateCourseFilter(
					$this, 
					array(
						'filter_object_type'	=>	'course', 
						'filter_object_id'		=>	$course->get_code()
					)
				);
					
				//  If the filter doesn't exist, create it
				if (!$filter->exists()) {
					$filter->create(array(
						'filter_object_type'	=>	'course',
						'filter_object_id'		=>	$course->get_code(),
						'filter_url_string'		=>	$course->get_code() . ' ' . $course->get_title(),
					));
				}

				// Get full URL for this course
				$full_course_url = $this->get_course_url($course, false, false, true);

				// We save only the relative part of the URL - when we render it
				// we'll calculate the full URL including hostname.
				$relative_course_url = substr($full_course_url, strlen(home_url()));
				$filter->save(array(
					'filter_object_path'	=>	$relative_course_url
				));
			
			}
			
			$this->show_notice(__('You have successfully refreshed the custom course URLs.', 'administrate'));
		
		//  Otherwise notify the user that there are no custom URLs
		} else if (isset($_POST['refresh_urls'])) {
			
			$this->show_notice(__('You have not set a custom course URL structure, so there is nothing to do.', 'administrate'));
			
		}
	
	}
	
	//  Save course URLs
	public function save_urls($params = array()) {
		
		//  Increase execution time limit
		set_time_limit(1800);
		
		//  Include the course filter
		require_once($this->get_path('/AdministrateCourseFilter.php'));

		//  Loop through the submitted IDs in reverse order to set categories/subcategories first
		for ($i = count($params['ids'])-1; $i >= 0; --$i) {
	
			//  Initialize the filter
			$filter = new AdministrateCourseFilter($this, $params['ids'][$i]);
			
			//  Figure out the value for the hidden field
			$hidden = 0;
			if (isset($params['hidden_ids']) && is_array($params['hidden_ids']) && in_array(strval($filter->get_id()), $params['hidden_ids'])) {
				$hidden = 1;
			}
			
			//  Set the fields to be saved
			$fields = array(
				'filter_hidden'				=>	$hidden,
				'filter_object_keywords'	=>	$params['keywords'][$i],
				'filter_object_description'	=>	$params['descriptions'][$i]
			);
			
			//  Only update the URL string if it's not empty
			$urlString = $params['url_strings'][$i];
			if (empty($urlString)) {
				if ($filter->get_object_type() == 'course') {
					$course = $course = $this->plugin->make_api_call('get_course_by_code', $filter->get_object_id());
					$fields['filter_url_string'] = $course->get_code() . ' ' . $course->get_title();
				} else if ($filter->get_object_type() == 'subcategory') {
					$subcategory = AdministrateAPI::get_subcategory($filter->get_object_id());
					$fields['filter_url_string'] = $subcategory->get_name();
				} else if ($filter->get_object_type() == 'category') {
					$category = AdministrateAPI::get_category($filter->get_object_id());
					$fields['filter_url_string'] = $category->get_name();
				}
			} else {
				$fields['filter_url_string'] = $urlString;
			}
	
			//  Save the filter
			$filter->save($fields);
	
		}
		
		//  Refresh all the object paths
		$this->refresh_urls();
		
		//  Show a message
		$this->show_notice(__('You have successfully updated SEO settings &amp; filters.', 'administrate'));
		
	}

	//  Install the plugin
	public function activate() {
		
		//  Call parent
		$result = parent::activate();
		
		//  Update the service mode to 'demo' if it is currently 'test'
		$mode = $this->get_option('mode', 'api');
		if ($mode == 'test') {
			$this->update_option('mode', 'api', 'demo');
		}
		
		//  If the domain API option doesn't exist but the subdomain does, automatically add the domain option
		$domain = $this->get_option('domain', 'api');
		$subdomain = $this->get_option('subdomain', 'api');
		if (empty($domain) && !empty($subdomain)) {
			$this->update_option('domain', 'api', $subdomain . '.administrateapp.com');
		}

		$this->setup_tables();

		//  Return the result
		return $result;
		
	}
	
	//  Migrate log data
	protected function _migrate_data() {
		
		//  Set the old tables
		foreach ($this->oldTables as $key=>$table) {
			$this->oldTables[$key] = $this->db->prefix.$table;
		}
		
		//  Check to see if the old tables exist
		$migrate = false;
		foreach ($this->oldTables as $table) {
			$tableExists = $this->db->query("SHOW TABLES LIKE '" . $table . "'");
			if ($tableExists) {
				$migrate = true;
				break;
			}
		}
		
		//  If any of the old tables exist, migrate
		if ($migrate) {
		
			//  Migrate the sessions / orders first because we'll need some data for the logs table
			$sessions = $this->db->get_results("SELECT * FROM " . $this->oldTables['sessions'] . " ORDER BY orderReference ASC");
			
			//  Loop through sessions
			if ($sessions) {
				$maxOrderId = 0;
				foreach ($sessions as $session) {
					
					//  Only proceed if there is an order reference
					if (!empty($session->orderReference)) {
					
						//  Extract the order ID
						$tmp = explode(":", $session->orderReference);
						$orderId = intval($tmp[1]);
	
						//  Get the order associated with the session
						$order = $this->db->get_row("SELECT * FROM " . $this->oldTables['orders'] . " WHERE orderId = " . $orderId . " LIMIT 1");
						
						//  Extract the buyer info and serialize
						$tmp = explode('^', $session->buyer);
						$buyerDetails = serialize(array(
							'first_name'	=>	$tmp[0],
							'last_name'		=>	$tmp[1],
							'company'		=>	$tmp[2],
							'email'			=>	$tmp[3],
							'phone'			=>	$tmp[4]
						));
						
						//  Extract the invoice address info and serialize
						$tmp = explode('^', $session->invoiceAddress);
						$invoiceAddress = serialize(array(
							'address'		=>	$tmp[0],
							'city'			=>	$tmp[1],
							'territory'		=>	$tmp[2],
							'postal_code'	=>	$tmp[3]
						));
						
						//  Extract the attendee info and serialize
						$tmp = explode('^', $session->attendees);
						$attendees = array();
						foreach ($tmp as $attendee) {
							if (!empty($attendee)) {
								$tmp2 = explode(':', $attendee);
								array_push(
									$attendees, 
									array(
										'name'	=>	$tmp2[0],
										'email'	=>	$tmp2[1]
									)
								);
							}
						}
						$attendeeDetails = serialize($attendees);
					
						//  Insert the data into the new orders table
						$this->db->insert(
							$this->get_orders_table(),
							array(
								'order_id'						=>	$orderId,
								'order_status'					=>	$order->orderStatus,
								'order_payment_type'			=>	$order->orderType,
								'order_time_started'			=>	0,
								'order_time_completed'			=>	strtotime($order->orderDate),
								'order_event_id'				=>	$order->eventId,
								'order_session_id'				=>	$session->sessionKey,
								'order_max_step'				=>	$session->phmax,
								'order_currency'				=>	$session->currency,
								'order_discount'				=>	$session->discount,
								'order_buyer_details'			=>	$buyerDetails,
								'order_invoice_address'			=>	$invoiceAddress,
								'order_num_attendees'			=>	$session->numberOfAttendees,
								'order_attendee_details'		=>	$attendeeDetails,
								'order_paypal_transaction_id'	=>	$order->paypalTransactionId
							)
						);
						
						//  Keep track of max order ID
						if ($orderId > $maxOrderId) {
							$maxOrderId = $orderId;
						}
					
					}
					
				}
				
				//  Set the auto increment start to the maximum order ID
				$this->db->query('ALTER TABLE ' . $this->get_orders_table() . ' AUTO_INCREMENT = ' . $maxOrderId);
				
			}
			
			//  Get all the data from the previous log table
			$logs = $this->db->get_results("SELECT * FROM " . $this->oldTables['logs'] . " ORDER BY logDate ASC");
			
			//  Loop through logs and insert them into the new logs table
			if ($logs) {
				$maxLogId = 0;
				foreach ($logs as $log) {
					
					//  Extract the order ID
					if (empty($log->orderReference)) {
						$orderId = 0;
					} else {
						$tmp = explode(":", $log->orderReference);
						$orderId = intval($tmp[1]);
					}
					
					//  Insert the data into the new logs table
					$this->db->insert(
						$this->get_logs_table(),
						array(
							'log_id'		=>	$log->logId,
							'log_msg'		=>	$log->logMsg,
							'log_time'		=>	strtotime($log->logDate),
							'log_order_id'	=>	$orderId
						)
					
					);
					
					//  Keep track of the max log ID
					if ($log->logId > $maxLogId) {
						$maxLogId = $log->logId;	
					}
					
				}
				
				//  Set the auto increment based on max log ID
				$this->db->query('ALTER TABLE ' . $this->get_logs_table() . ' AUTO_INCREMENT = ' . $maxLogId);
				
			}
			
			//  Finally, delete all the old tables
			foreach ($this->oldTables as $table) {
				$this->db->query('DROP TABLE ' . $table);
			}
			
		}
				
	}
	
	//  Test API connection
	public function test_api_connection() {
		$startTime = microtime(true);
		$this->init_api(true);
		$success = AdministrateAPI::init_soap_client(true);
		$endTime = microtime(true);
		if ($success) {
			$numMs = number_format($endTime - $startTime, 4);
			$this->show_notice(__('Connection <strong>successful</strong> in <strong>' . $numMs . ' seconds</strong>.', 'administrate'));
		} else {
			$this->show_error(__('Connection <strong>failed</strong>.', 'administrate'));
		}
	}
	
	//  Test API performance
	public function test_api_performance() {
		$methods = array(
			'get_events',
			'get_categories',
			'get_courses',
			'get_locations'
		);
		$msg = 'Performance tests: ';
		foreach ($methods as $method) {
			$startTime = microtime(true);
			$result = $this->make_api_call($method, array(), true);
			$endTime = microtime(true);
			$numMs = number_format($endTime - $startTime, 4);
			$msg .= '<strong>' . $method . '</strong> in <strong>' . $numMs . '</strong> seconds, ';
		}
		$msg = substr($msg, 0, -2) . '.';
		$this->show_notice(__($msg, 'administrate'));
	}
	
	//  Test API consistency
	public function test_api_consistency() {
		set_time_limit(1800);
		$consistent = true;
		$methods = array(
			'get_events',
			'get_categories',
			'get_courses',
			'get_locations'
		);
		foreach ($methods as $method) {
			if (isset($result)) {
				unset($result);	
			}
			for ($i = 0; $i < 10; ++$i) {
				$tmpResult = count($this->make_api_call($method, array(), true));
				if (isset($result) && ($tmpResult != $result)) {
					$consistent = false;
				}
				$result = $tmpResult;
			}
		}
		if ($consistent) {
			$this->show_notice(__('API consistency test <strong>successful</strong>.', 'administrate'));
		} else {
			$this->show_error(__('API consistency test <strong>failed</strong>.', 'administrate'));
		}
	}

	//  Get the cache table
	public function get_caches_table() {
		return $this->get_table('caches');
	}
	
	//  Get the orders table
	public function get_orders_table() {
		return $this->get_table('orders');	
	}
	
	//  Get the logs table
	public function get_logs_table() {
		return $this->get_table('logs');	
	}
	
	//  Get the filters table
	public function get_filters_table() {
		return $this->get_table('filters');	
	}
	
	//  Filter text output
	public function filter_text_output($str) {
		$str = trim($str);
		if ($str != strip_tags($str)) {	
			return $str;
		} else {
			return nl2br($str);
		}
	}
	
	//  Encode base64/serialize a string
	protected function _encode($str) {
		return base64_encode(serialize($str));
	}
	
	//  Decode base64/unserialize a string
	protected function _decode($str) {
		return unserialize(base64_decode($str));	
	}
	
	//  Whether the current page is generated
	public function page_is_generated() {
		return $this->controller->page_is_generated();	
	}
	
}
