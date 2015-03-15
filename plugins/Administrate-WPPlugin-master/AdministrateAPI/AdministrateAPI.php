<?php
//  Include required API objects
$path = dirname(__FILE__);
require_once($path.'/AdministrateAPIObject.php');
require_once($path.'/AdministrateAPIEvent.php');
require_once($path.'/AdministrateAPIEventPrice.php');
require_once($path.'/AdministrateAPICourse.php');
require_once($path.'/AdministrateAPICourseSubcategory.php');
require_once($path.'/AdministrateAPICourseCategory.php');
require_once($path.'/AdministrateAPILocation.php');
require_once($path.'/AdministrateAPIOrder.php');
require_once($path.'/AdministrateAPIUser.php');
require_once($path.'/AdministrateAPIDelegate.php');
require_once($path.'/AdministrateAPICartEntry.php');
require_once($path.'/AdministrateAPIDelegateCartEntry.php');
require_once($path.'/AdministrateAPIOrderDetails.php');
require_once($path.'/AdministrateAPIOrderPayment.php');

//  Administrate backend API layer
class AdministrateAPI {
	
	//  Properties
	public static $soapClient = false;
	private static $debug = true;
	private static $logTypes = array(
		'error'		=>	E_USER_ERROR,
		'warning'	=>	E_USER_WARNING,
		'notice'	=>	E_USER_NOTICE
	);
	private static $logs = array();
	private static $lastLogTime = 0;
	private static $caching = true;
	private static $cache = array();
	public static $initialized = false;
	private static $url;
	private static $user;
	private static $password;
	
	//  Initialize the API
	public static function init($url, $user, $password, $debug = false) {
		
		//  Set debug mode
		self::$debug = $debug;
		
		//  Set the last log time
		self::$lastLogTime = microtime(true) * 1000000;
		
		//  Set connection settings
		self::$url = $url;
		self::$user = $user;
		self::$password = $password;
		
		//  Set flag to indicate the API has been initialized
		self::$initialized = true;
		
	}
	
	/* **** ORDER MANAGEMENT *** */
	
	//  Place an order
	public static function submit_order($fields = array()) {
		$order = new AdministrateAPIOrder();
		return $order->add($fields);	
	}
	
	//  Send invoice
	public static function send_invoice($orderNum) {
		try {
			self::log('Send invoice for order #' . $orderNum);
			return new AdministrateAPIEvent(self::make_soap_call('sendInvoice', $orderNum));
		} catch(Exception $e) {
			self::log('Error sending invoice #' . $orderNum . ': ' .$e->getMessage(), 'error');
			return false;
		}
	}
	
	/* **** EVENT MANAGEMENT *** */
	
	// Get a specific event
	public static function get_event($eid) {
		try {
			self::log('Get event ID ' . $eid);
			return new AdministrateAPIEvent(self::get_data('getEventByID', array($eid)));
		} catch(Exception $e) {
			self::log('Error getting events: '.$e->getMessage(), 'error');
		}
	}
	
	//  Get events
	public static function get_events($params = array()) {

		//  Set default options
		$params = array_merge(
			array(
				'IncludePrices'		=>	true
			),
			$params
		);
		
		//  Try to query for the events
		try {
			
			//  Make the SOAP call
			$ret = self::get_data('getEvents', array('Filter'=>$params));
			
			//  Loop through events
			$events = array();
			$i = 0;
			if (isset($ret->EventList)) {
				foreach ($ret->EventList as $event) {
					
					//  Initialize the event object
					$event = new AdministrateAPIEvent($event);
					
					//  Only proceeed if the event is not provisional
					if (!$event->is_provisional()) {

						//  If a subcategory was passed in, make sure it matches before proceeding
						$addEvent = false;
						if (isset($params['CourseSubCategoryID']) && isset($event->CourseCategories)) {
							foreach ($event->CourseCategories as $category) {
								foreach ($category->SubCategories as $subcategory) {
									if ($subcategory->SubCategoryID == $params['CourseSubCategoryID']) {
										$addEvent = true;
									}
								}
							}

						//  Otherwise we'll go ahead with it
						} else {
							$addEvent = true;
						}

						//  Add the event to the array
						if ($addEvent) {
							$events[$event->get_start_date() . '-' . $i] = $event;
						}

					}
					++$i;
				}
			}
			
			//  Sort the events by key
			ksort($events);
			
			self::log('Get events');
			
			//  Return the vetted events
			return $events;
			
		} catch(Exception $e) {
			self::log('Error getting events: '.$e->getMessage(), 'error');
			return false;
		}
		
	}
	
	//  Get courses from events
	public static function get_courses_from_events($events) {
		
		//  Loop through events and add unique courses
		$courses = array();
		foreach ($events as $event) {
			$courseCode = $event->get_course_code();
			if (!isset($courses[$courseCode])) {
				$courses[$courseCode] = self::get_course_by_code($courseCode);
			}
		}
		ksort($courses);
		
		//  Return the courses sorted by code
		return $courses;
		
	}
	
	//  Get unique course categories based on events
	public static function get_categories_from_events($events) {
		$categories = array();
		foreach ($events as $event) {
			foreach ($event->get_categories() as $category) {
				$key = self::_str_to_key($category->get_name());
				if (!isset($categories[$key])) {
					$categories[$key] = $category;	
				}
			}
		}
		ksort($categories);
		return $categories;
	}
	
	//  Get unique locations
	public function get_locations_from_events($events) {
		$locations = array();
		foreach ($events as $event) {
			$key = self::_str_to_key($event->get_location());
			if (!isset($locations[$key])) {
				$locations[$key] = $event->get_location();	
			}
		}
		ksort($locations);
		return $locations;
	}
	
	//  Get number of event delegates
	public function get_event_num_delegates($eventId) {
		return self::get_data('getNumDelegates', array($eventId));
	}
	
	/* **** COURSE MANAGEMENT *** */
	
	//  Get courses
	public static function get_courses($params = array()) {

		//  Try to query for the events
		try {
			
			//  Make the SOAP call
			$ret = self::get_data('getCourses', $params);
			
			//  Loop through events
			$courses = array();
			if (property_exists($ret, 'CourseList')) {
				foreach ($ret->CourseList as $course) {
					
					//  Initialize the event object
					$course = new AdministrateAPICourse($course);
					
					//  Check category ID
					$include = true;
					if (isset($params['CourseCategoryID'])) {
						$include = self::_categories_contain_id($params['CourseCategoryID'], $course->get_categories());	
					}
				
					//  Add the course to the array
					if ($include) {
						$courses[$course->get_code()] = $course;	
					}
					
				}
			}
			
			//  Sort the events by key
			ksort($courses);
			
			self::log('Get courses');
			
			//  Return the vetted events
			return $courses;
			
		} catch(Exception $e) {
			self::log('Error getting courses: '.$e->getMessage(), 'error');
			return false;
		}
		
	}
	
	//  Get a course by code
	public static function get_course_by_code($code) {
		try {
			self::log('Get course by code: ' . $code);
			return new AdministrateAPICourse(self::get_data('getCourseByCode', array($code)));
		} catch(Exception $e) {
			self::log('Error getting course: '.$e->getMessage(), 'error');
		}
	}
	
	//  Get categories
	public static function get_categories($includeEmpty = true) {
		
		//  If we need all categories, hit the API
		if ($includeEmpty) {
			
			try {
				
				//  Make the SOAP call
				$ret = self::get_data('getCategories', array());
				
				//  Loop through categories
				$categories = array();
				foreach ($ret->CategoryList as $category) {
					array_push($categories, new AdministrateAPICourseCategory($category));
				}
				
				self::log('Get courses categories');
				
				//  Return the vetted categories
				return $categories;
				
			} catch(Exception $e) {
				self::log('Error getting course categories '.$e->getMessage(), 'error');
			}
		
		//  If we need only non-empty categories, this is going to be a bit more tricky ...
		} else {
			
			//  Get all upcoming events
			$events = self::get_events();
			
			//  Loop through events and add unique categories
			$categories = array();
			foreach ($events as $event) {
				$tmpCategories = $event->get_categories();
				if ($tmpCategories) {
					foreach ($tmpCategories as $category) {
						$key = self::_str_to_key($category->get_name());
						if (!isset($categories[$key])) {
							$categories[$key] = $category;
						}
					}
				}
			}
			
			//  Resort based on alphabetic key
			ksort($categories);
			
			//  Return the alphabetized categories
			return $categories;
			
		}
		
	}
	
	//  Get a single category
	public static function get_category($categoryId) {
	
		//  Get all categories
		$categories = self::get_categories();
		
		//  Loop through categories until we find the right one
		$category = false;
		foreach ($categories as $tmpCategory) {
			if ($tmpCategory->get_id() == $categoryId) {
				$category = $tmpCategory;
				break;	
			}
		}
		
		//  Return the category
		return $category;
		
	}
	
	//  Get a single subcategory
	public static function get_subcategory($subcategoryId, $categoryId = false) {
	
		//  If a category ID is defined, use it instead of all categories
		if ($categoryId) {
			$categories = array(self::get_category($categoryId));
		} else {
			$categories = self::get_categories();	
		}
		
		//  Loop through categories until we find the right subcategory
		$subcategory = false;
		foreach ($categories as $tmpCategory) {
			$subcategories = $tmpCategory->get_subcategories();
			if (count($subcategories) > 0) {
				foreach ($subcategories as $tmpSubcategory) {					
					if ($tmpSubcategory->get_id() == $subcategoryId) {
						$subcategory = $tmpSubcategory;
						break;	
					}
				}
			}
		}
		
		//  Return the subcategory
		return $subcategory;
		
	}
	
	/* **** LOCATION MANAGEMENT *** */
	
	//  Get locations
	public static function get_locations() {
		try {
			
			//  Make the SOAP call
			$ret = self::get_data('getLocations', array());
			
			//  Loop through locations
			$locations = array();
			foreach ($ret->LocationList as $location) {
				array_push($locations, new AdministrateAPILocation($location));
			}
			
			//  Log action
			self::log('Get locations');
			
			//  Return the vetted locations
			return $locations;
			
		} catch(Exception $e) {
			self::log('Error getting locations '.$e->getMessage(), 'error');
		}
	}
	
	/* **** USER MANAGEMENT *** */
	
	//  Add a user
	public static function add_user($fields = array()) {
		$user = new AdministrateAPIUser();
		return $user->add($fields);	
	}
	
	//  Get a user by email
	public static function get_user_by_email($email) {
		$user = new AdministrateAPIUser();
		return $user->get_by_email($email);
	}
	
	/* **** DELEGATE MANAGEMENT *** */
	
	//  Add a delegate
	public static function add_delegate($fields = array()) {
		$delegate = new AdministrateAPIDelegate();
		return $delegate->add($fields);	
	}
	
	/* **** API CLIENT HELPERS *** */
	
	//  Initialize SOAP client
	public static function init_soap_client($force = false) {
		
		//  Only proceed if the SOAP client isn't already initialized
		if (!self::$soapClient || $force) {
		
			try {

				//  Throw an exception if we're missing any required parameters
				if (empty(self::$url)) {
					self::log('URL not set', 'error');
				} else if (empty(self::$user)) {
					self::log('username not set', 'error');
				} else if (empty(self::$password)) {
					self::log('password not set', 'error');
				}

				//  Disable the soap cache
				ini_set('soap.wsdl_cache_enabled', 0);
				
				//  Initialize the SOAP client
				self::$soapClient = new SoapClient(
					self::$url,
					array(
						'trace'		=>	self::$debug,
						'exception'	=>	1,
						'login'		=>	self::$user,
						'password'	=>	self::$password,
						'features'	=>	SOAP_SINGLE_ELEMENT_ARRAYS
					)
				);
				
				self::log('SoapClient initialized');
				return true;
				
			} catch(Exception $e) {
				self::log('Error initiating SoapClient: '.$e->getMessage(), 'error');
				return false;
			}
		
		}
		
	}

	//  Convert the soap variables
	private static function _convert_soap_vars(array $request) {
		
		//  If the cart data exists ...
		if (isset($request['cartData'])) {
			
			//  Loop through variables and replace each one with a SOAP variable
			$newCartData = array();
			foreach ($request['cartData'] as $entry) {
				switch (strtolower($entry['EntryType'])) {
					case 'delegate':
						$objectType = 'DelegateCartEntry';
						break;
					case 'item':
						$objectType = 'ItemCartEntry';
						break;
					default:
						throw new Exception("Unknown EntryType {$entry['EntryType']}");
				}
				$newCartData[] = new SoapVar(
					$entry,
					XSD_ANYTYPE,
					$objectType,
					'urn:EglWdPublic'
				);
			}
		
			//  Save the new SOAP variables
			$request['cartData'] = $newCartData;
			
		}
		
		return $request;
	
	}

	//  Whether or not a set of categories include the desired ID
	private static function _categories_contain_id($id, $categories = false) {
		
		//  If no set of categories was passed, get them all
		if (!$categories) {
			$categories = self::get_categories();	
		}
		
		//  Assume the negative
		$contains = false;
		
		//  Loop through categories
		foreach ($categories as $category) {
			
			//  If the category matches the ID, result is positive and break loop
			if ($category->get_id() == $id) {
				$contains = true;
				break;	
			}
			
			// Loop through subcategories
			foreach ($category->get_subcategories() as $subcategory) {
				
				//  If the subcategory matches the ID, result is positive and break loop x2
				if ($subcategory->get_id() == $id) {
					$contains = true;
					break 2;	
				}
				
			}
			
		}
		
		return $contains;
	
	}

	/**
	 * This used to handle some intermediary caching, and simply wrap make_soap_call,
	 * but testing found this provided little benefit, and was prone to inducing errors.
	 */
	public static function get_data($method, $args = array(), $noCache = false) {

		if($error = AdministrateAPI::validate_api_call($method, $args)) {
			error_log('API Call Validation Error: ' . $error);
		}

		//  Return the result
		return self::make_soap_call($method, $args);

	}

	//  Make a SOAP Call
	public static function make_soap_call($method, $args) {

		// If the SOAP client hasn't been initialized, do it now
		if (!self::$soapClient) {
			self::init_soap_client();
			//trigger_error('API not initialized! Trying to run method: ' . $method);
		}

		// If the arguments aren't already an array, put the value into an array
		if (!is_array($args)) {
			$args = array($args);
		}

		// Try to make the SOAP call
		try {
			$result = self::$soapClient->__soapCall($method, $args);
			self::log('SOAP Call: ' . $method);
			return $result;
		} catch (Exception $e) {
			self::log('Error making SOAP call: '.$e->getMessage(), 'warning');
			return false;
		}
		
	}
	
	//  Get the cache
	public static function get_cache() {
		return self::$cache;	
	}
	
	//  Log an event
	public static function log($msg, $type = 'notice') {
		$currentTime = microtime(true) * 1000000;
		$timeLapse = $currentTime - self::$lastLogTime;
		array_push(self::$logs, $type . ': ' . $msg . ' (' . $timeLapse . 'ms)');
		if (self::$debug) {
			trigger_error('Administrate API: ' . $msg . ' (' . $timeLapse . 'ms)', self::$logTypes[$type]);
		}
		self::$lastLogTime = $currentTime;
		if ($type == 'error') {
			exit;	
		}
	}
	
	//  Get all the logs
	public static function get_logs() {
		return self::$logs;	
	}
	
	//  Convert a string to an array key
	private static function _str_to_key($str) {
		$str = preg_replace('/[^a-z]/i', '', strtolower($str));
		return $str;
	}

	public static function validate_api_call($method, $args) {

		// args validated as arrays, but can be null for some function calls
		if(is_null($args)) {
			$args = array();
		}

		// Non-array params are wrapped in an array, so grab first element.
		// Array-based params use named keys, so this won't fire.
		if(array_key_exists(0, $args)) {
			$args = $args[0];
		} elseif(array_key_exists('Filter', $args)) {
			// Fix for getEvents arguements which are contained in 'Filter' key
			$args = $args['Filter'];
		}

		// $methods is an associative array of potential API calls.
		// The keys are the method names, the values are either an
		// array of possible arguments, or a filter_var constant if
		// not an array.
		$methods = array(
			'getEventByID' => FILTER_VALIDATE_INT,
			'getEvents' => array(
				'CourseCode', 'CourseCategoryID', 'EventID', 'EventStartDate',
				'EventLocationName', 'IncludePrices', 'LocationRegionID', 'SortBy'
			),
			'getNumDelegates' => FILTER_VALIDATE_INT,
			'getCourses' => array(
				'CourseID', 'Disposition', 'IncludeCancelled', 'IncludeCustomFields', 'CourseCategoryID'
			),
			'getCourseByCode' => FILTER_SANITIZE_STRING,
			'getCategories' => array(),
			'addUser' => array(
				'IsIndividual', 'Email', 'DontMail', 'Password', 'Company',
				'FirstName', 'LastName', 'JobTitle', 'Department',
				'Address1', 'Address2', 'Address3', 'City',
				'County', 'PostCode', 'CountryCode', 'Tel',
				'Mobile', 'HearAboutUs', 'HearAboutUsText', 'ContactID',
				'CustomDate1', 'CustomDate2', 'CustomDropDown1'
			),
			'getUserByEmail' => FILTER_SANITIZE_STRING,
			'addDelegate' => array(
				'WebsiteUserID', 'Email', 'FirstName', 'LastName',
				'JobTitle', 'Department', 'Address1', 'Address2',
				'Address3', 'City', 'County', 'PostCode',
				'Country', 'Tel', 'Fax', 'Mobile', 'DelegateNotes'
			)
		);

		$error = false;
		if(array_key_exists($method, $methods)) {
			if(is_array($methods[$method])) {
				if(array_key_exists(0, $args)) {
					$vals = $args;
				} else {
					$vals = array_keys($args);
				}
				foreach($vals as $arg) {
					if(is_object($arg) || count($methods[$method]) > 0) {
						continue;
					}
					if(!in_array($arg, $methods[$method])) {
						$error = 'Argument ' . $arg . ' not valid in ' . $method . ' checked against ' . serialize($methods[$method]);
					}
				}
			} elseif(!filter_var($args, $methods[$method])) {
				$error = 'Argument for ' . $method . ' must be ' . $methods[$method] . ' given ' . $args;
			}
		} else {
			$error = 'Method ' . $method . ' not found.';
		}

		// $error is false or a string with an error.
		return $error;
	}
}

