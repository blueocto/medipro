<?php

// Define checkout steps so that we can
// view them in a textual fashion.
define('ADM_CHECKOUT_STEP_COURSE', 1);
define('ADM_CHECKOUT_STEP_ATTENDEE', 2);
define('ADM_CHECKOUT_STEP_TERMS', 3);
define('ADM_CHECKOUT_STEP_CONFIRM', 4);
define('ADM_CHECKOUT_STEP_PLACE', 5);
define('ADM_CHECKOUT_STEP_IPN', 6);

//  Administrate checkout widget
class AdministrateWidgetCheckout extends WPPluginPatternWidget {

	//  Configuration
	protected $key = 'checkout';
	private $orderKey = 'order_id';
	private $eventKey = 'event_id';
	private $sessionKey = 'order_session_id';
	private $stepKey = 'step';
	private $buyerFields;
	private $invoiceFields;
	private $attendeeFields;
	private $jsonFlag = '<!-- ADMINISTRATE_JSON_START -->';
	private $stepFields = array(
		1	=>	array(
			'order_currency',
			'order_num_attendees',
			'order_max_step'
		),
		2	=>	array(
			'order_buyer_details',
			'order_invoice_address',
			'order_attendee_details',
			'order_max_step'
		),
		3	=>	array(
			'tac',
			'order_max_step'
		),
		4	=>	array(
			'order_max_step'
		),
		5	=>	array(
			'order_status',
			'order_payment_type',
			'order_processor_transaction_id',
			'order_max_step'
		)
	);
	private $countries = array(
		"AF"	=>	"Afghanistan",
		"AX"	=>	"Aland Islands",
		"AL"	=>	"Albania",
		"DZ"	=>	"Algeria",
		"AS"	=>	"American Samoa",
		"AD"	=>	"Andorra",
		"AO"	=>	"Angola",
		"AI"	=>	"Anguilla",
		"AQ"	=>	"Antarctica",
		"AG"	=>	"Antigua and Barbuda",
		"AR"	=>	"Argentina",
		"AM"	=>	"Armenia",
		"AW"	=>	"Aruba",
		"AC"	=>	"Ascension Island",
		"AU"	=>	"Australia",
		"AT"	=>	"Austria",
		"AZ"	=>	"Azerbaijan",
		"BS"	=>	"Bahamas",
		"BH"	=>	"Bahrain",
		"BB"	=>	"Barbados",
		"BD"	=>	"Bangladesh",
		"BY"	=>	"Belarus",
		"BE"	=>	"Belgium",
		"BZ"	=>	"Belize",
		"BJ"	=>	"Benin",
		"BM"	=>	"Bermuda",
		"BT"	=>	"Bhutan",
		"BW"	=>	"Botswana",
		"BO"	=>	"Bolivia",
		"BA"	=>	"Bosnia and Herzegovina",
		"BV"	=>	"Bouvet Island",
		"BR"	=>	"Brazil",
		"IO"	=>	"British Indian Ocean Territory",
		"BN"	=>	"Brunei Darussalam",
		"BG"	=>	"Bulgaria",
		"BF"	=>	"Burkina Faso",
		"BI"	=>	"Burundi",
		"KH"	=>	"Cambodia",
		"CM"	=>	"Cameroon",
		"CA"	=>	"Canada",
		"CV"	=>	"Cape Verde",
		"KY"	=>	"Cayman Islands",
		"CF"	=>	"Central African Republic",
		"TD"	=>	"Chad",
		"CL"	=>	"Chile",
		"CN"	=>	"China",
		"CX"	=>	"Christmas Island",
		"CC"	=>	"Cocos (Keeling) Islands",
		"CO"	=>	"Colombia",
		"KM"	=>	"Comoros",
		"CG"	=>	"Congo",
		"CD"	=>	"Congo, Democratic Republic",
		"CK"	=>	"Cook Islands",
		"CR"	=>	"Costa Rica",
		"CI"	=>	"Cote D'Ivoire (Ivory Coast)",
		"HR"	=>	"Croatia (Hrvatska)",
		"CU"	=>	"Cuba",
		"CY"	=>	"Cyprus",
		"CZ"	=>	"Czech Republic",
		"DK"	=>	"Denmark",
		"DJ"	=>	"Djibouti",
		"DM"	=>	"Dominica",
		"DO"	=>	"Dominican Republic",
		"TP"	=>	"East Timor",
		"EC"	=>	"Ecuador",
		"EG"	=>	"Egypt",
		"SV"	=>	"El Salvador",
		"GQ"	=>	"Equatorial Guinea",
		"ER"	=>	"Eritrea",
		"EE"	=>	"Estonia",
		"ET"	=>	"Ethiopia",
		"FK"	=>	"Falkland Islands (Malvinas)",
		"FO"	=>	"Faroe Islands",
		"FJ"	=>	"Fiji",
		"FI"	=>	"Finland",
		"FR"	=>	"France",
		"FX"	=>	"France, Metropolitan",
		"GF"	=>	"French Guiana",
		"PF"	=>	"French Polynesia",
		"TF"	=>	"French Southern Territories",
		"GA"	=>	"Gabon",
		"GM"	=>	"Gambia",
		"GE"	=>	"Georgia",
		"DE"	=>	"Germany",
		"GH"	=>	"Ghana",
		"GI"	=>	"Gibraltar",
		"GR"	=>	"Greece",
		"GL"	=>	"Greenland",
		"GD"	=>	"Grenada",
		"GP"	=>	"Guadeloupe",
		"GU"	=>	"Guam",
		"GT"	=>	"Guatemala",
		"GN"	=>	"Guinea",
		"GW"	=>	"Guinea-Bissau",
		"GY"	=>	"Guyana",
		"HT"	=>	"Haiti",
		"HM"	=>	"Heard and McDonald Islands",
		"HN"	=>	"Honduras",
		"HK"	=>	"Hong Kong",
		"HU"	=>	"Hungary",
		"IS"	=>	"Iceland",
		"IN"	=>	"India",
		"ID"	=>	"Indonesia",
		"IR"	=>	"Iran",
		"IQ"	=>	"Iraq",
		"IE"	=>	"Ireland",
		"IL"	=>	"Israel",
		"IM"	=>	"Isle of Man",
		"IT"	=>	"Italy",
		"JE"	=>	"Jersey",
		"JM"	=>	"Jamaica",
		"JP"	=>	"Japan",
		"JO"	=>	"Jordan",
		"KZ"	=>	"Kazakhstan",
		"KE"	=>	"Kenya",
		"KI"	=>	"Kiribati",
		"KP"	=>	"Korea (North)",
		"KR"	=>	"Korea (South)",
		"KW"	=>	"Kuwait",
		"KG"	=>	"Kyrgyzstan",
		"LA"	=>	"Laos",
		"LV"	=>	"Latvia",
		"LB"	=>	"Lebanon",
		"LI"	=>	"Liechtenstein",
		"LR"	=>	"Liberia",
		"LY"	=>	"Libya",
		"LS"	=>	"Lesotho",
		"LT"	=>	"Lithuania",
		"LU"	=>	"Luxembourg",
		"MO"	=>	"Macau",
		"MK"	=>	"Macedonia (F.Y.R.O.M.)",
		"MG"	=>	"Madagascar",
		"MW"	=>	"Malawi",
		"MY"	=>	"Malaysia",
		"MV"	=>	"Maldives",
		"ML"	=>	"Mali",
		"MT"	=>	"Malta",
		"MH"	=>	"Marshall Islands",
		"MQ"	=>	"Martinique",
		"MR"	=>	"Mauritania",
		"MU"	=>	"Mauritius",
		"YT"	=>	"Mayotte",
		"MX"	=>	"Mexico",
		"FM"	=>	"Micronesia",
		"MD"	=>	"Moldova",
		"MC"	=>	"Monaco",
		"ME"	=>	"Montenegro",
		"MS"	=>	"Montserrat",
		"MA"	=>	"Morocco",
		"MZ"	=>	"Mozambique",
		"MM"	=>	"Myanmar",
		"NA"	=>	"Namibia",
		"NR"	=>	"Nauru",
		"NP"	=>	"Nepal",
		"NL"	=>	"Netherlands",
		"AN"	=>	"Netherlands Antilles",
		"NT"	=>	"Neutral Zone",
		"NC"	=>	"New Caledonia",
		"NZ"	=>	"New Zealand (Aotearoa)",
		"NI"	=>	"Nicaragua",
		"NE"	=>	"Niger",
		"NG"	=>	"Nigeria",
		"NU"	=>	"Niue",
		"NF"	=>	"Norfolk Island",
		"MP"	=>	"Northern Mariana Islands",
		"NO"	=>	"Norway",
		"OM"	=>	"Oman",
		"PK"	=>	"Pakistan",
		"PW"	=>	"Palau",
		"PS"	=>	"Palestinian Territory, Occupied",
		"PA"	=>	"Panama",
		"PG"	=>	"Papua New Guinea",
		"PY"	=>	"Paraguay",
		"PE"	=>	"Peru",
		"PH"	=>	"Philippines",
		"PN"	=>	"Pitcairn",
		"PL"	=>	"Poland",
		"PT"	=>	"Portugal",
		"PR"	=>	"Puerto Rico",
		"QA"	=>	"Qatar",
		"RE"	=>	"Reunion",
		"RO"	=>	"Romania",
		"RU"	=>	"Russian Federation",
		"RW"	=>	"Rwanda",
		"GS"	=>	"S. Georgia and S. Sandwich Isls.",
		"KN"	=>	"Saint Kitts and Nevis",
		"LC"	=>	"Saint Lucia",
		"VC"	=>	"Saint Vincent & the Grenadines",
		"WS"	=>	"Samoa",
		"SM"	=>	"San Marino",
		"ST"	=>	"Sao Tome and Principe",
		"SA"	=>	"Saudi Arabia",
		"SN"	=>	"Senegal",
		"RS"	=>	"Serbia",
		"SC"	=>	"Seychelles",
		"SL"	=>	"Sierra Leone",
		"SG"	=>	"Singapore",
		"SI"	=>	"Slovenia",
		"SK"	=>	"Slovak Republic",
		"SB"	=>	"Solomon Islands",
		"SO"	=>	"Somalia",
		"ZA"	=>	"South Africa",
		"ES"	=>	"Spain",
		"LK"	=>	"Sri Lanka",
		"SH"	=>	"St. Helena",
		"PM"	=>	"St. Pierre and Miquelon",
		"SD"	=>	"Sudan",
		"SR"	=>	"Suriname",
		"SJ"	=>	"Svalbard & Jan Mayen Islands",
		"SZ"	=>	"Swaziland",
		"SE"	=>	"Sweden",
		"CH"	=>	"Switzerland",
		"SY"	=>	"Syria",
		"TW"	=>	"Taiwan",
		"TJ"	=>	"Tajikistan",
		"TZ"	=>	"Tanzania",
		"TH"	=>	"Thailand",
		"TG"	=>	"Togo",
		"TK"	=>	"Tokelau",
		"TO"	=>	"Tonga",
		"TT"	=>	"Trinidad and Tobago",
		"TN"	=>	"Tunisia",
		"TR"	=>	"Turkey",
		"TM"	=>	"Turkmenistan",
		"TC"	=>	"Turks and Caicos Islands",
		"TV"	=>	"Tuvalu",
		"UG"	=>	"Uganda",
		"UA"	=>	"Ukraine",
		"AE"	=>	"United Arab Emirates",
		"UK"	=>	"United Kingdom", //  GB????
		"US"	=>	"United States",
		"UM"	=>	"US Minor Outlying Islands",
		"UY"	=>	"Uruguay",
		"UZ"	=>	"Uzbekistan",
		"VU"	=>	"Vanuatu",
		"VA"	=>	"Vatican City State (Holy See)",
		"VE"	=>	"Venezuela",
		"VN"	=>	"Viet Nam",
		"VG"	=>	"British Virgin Islands",
		"VI"	=>	"Virgin Islands (U.S.)",
		"WF"	=>	"Wallis and Futuna Islands",
		"EH"	=>	"Western Sahara",
		"YE"	=>	"Yemen",
		"ZM"	=>	"Zambia",
		"ZW"	=>	"Zimbabwe"
	);
	
	//  Properties
	private $order = false;
	private $paymentProcessor = false;
	
	//  Constructor
	public function __construct(&$plugin) {
		
		//  Call parent constructor
		parent::__construct($plugin);

		//  Save the global variable names w/ namespacing
		$this->orderKey = $this->add_namespace($this->orderKey);
		$this->eventKey = $this->add_namespace($this->eventKey);
		$this->stepKey = $this->add_namespace($this->stepKey);
		
		//  Add the jQuery plugin & CSS
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script($this->add_namespace(''), $this->_get_url('/jquery.administrate_checkout.js'), array('jquery', 'jquery-ui-tabs', 'jquery-ui-accordion'), false, true);
		wp_enqueue_style($this->add_namespace(''), $this->_get_url('/style.css'));
		
		//  Start the session
		@session_start();
				
	}
	
	//  Run the checkout
	public function run() {

		/* This function:
		 *      Performs a pre-flight check to make darn sure there are still spaces on the event
		 *      Loads the payment processor if required
		 *      Attempts to fetch an order
		 *      If we don't have an order:
		 *          Show the events table and STOP
		 *      If we are processing an IPN response:
		 *          Process it and STOP
		 *      Validates any new information
		 *      Performs some step-specific logic, including ensuring the T's & C's were accepted
		 *      Runs the appropriate checkout step
		 */

		// If we've got an event to work with, pull a fresh copy of it from the DB and check if we actually have spaces
		if(array_key_exists($this->eventKey, $_REQUEST) && $_REQUEST[$this->eventKey] > 0) {
			$pre_flight = $this->plugin->make_api_call('get_event', $_REQUEST[$this->eventKey], true);

			// check if the event is sold out, honouring the setting to check 'places'
			if($pre_flight->is_sold_out($this->plugin->to_boolean($this->plugin->get_option('translate_places_to_status', 'event')))) {

				// update the event and return to the event table
				$this->plugin->update_individual_event($_REQUEST[$this->eventKey], $pre_flight);
				return $this->plugin->display_widget('event_table', array('errors' => 'Unfortunately, the course you have selected is now sold out. Please choose another event.'));
			}
		}


		// Include order and log DAO's
		require_once($this->get_path('/AdministrateWidgetCheckoutOrder.php'));
		require_once($this->get_path('/AdministrateWidgetCheckoutLog.php'));

		$log = new AdministrateWidgetCheckoutLog($this->plugin);

		//  Figure out what step we're on
		if (!isset($_REQUEST[$this->stepKey]) || !is_numeric($_REQUEST[$this->stepKey])) {
			$current_step = constant('ADM_CHECKOUT_STEP_COURSE');
		} else {
			$current_step = intval($_REQUEST[$this->stepKey]);
		}

		// If we're enabling pay by CC and we're passed the terms step
		if(intval($this->get_option('payment_by_cc')) && $current_step >= constant('ADM_CHECKOUT_STEP_CONFIRM')) {

			// Require the payment processor class
			require_once($this->get_path('/AdministrateWidgetCheckoutPaymentProcessor.php'));

			// Now load the class specific to the processor we're using
			$class = __CLASS__ . 'PaymentProcessor' . $this->get_option('payment_processor');
			$classPath = $this->get_path('/' . $class . '.php');
			if (file_exists($classPath)) {
				require_once($classPath);
				$this->paymentProcessor = new $class($this->plugin, $this);
				if ($this->paymentProcessor->order && $this->paymentProcessor->order->exists()) {

					// Grab the order
					$this->order = $this->paymentProcessor->get_order();
				}
			}
		}

		// Try and fetch order
		if(!$this->order) {

			// If a zero event ID was passed, reset the session
			if(isset($_REQUEST[$this->eventKey]) && (intval($_REQUEST[$this->eventKey]) == 0)) {
				$this->order = false;
				if(isset($_SESSION[$this->orderKey])) {
					unset($_SESSION[$this->orderKey]);
				}

			// Otherwise try to bring up the order in the session
			} else {

				// Take order key from session if we can, otherwise check if it's set in postdata
				$where = array();
				if(isset($_SESSION[$this->orderKey])) {
					$where[$this->strip_namespace($this->orderKey)] = $_SESSION[$this->orderKey];
				} elseif(isset($_POST[$this->orderKey])) {
					$where[$this->strip_namespace($this->orderKey)] = $_POST[$this->orderKey];
				}

				// If we have anything to query for the order, query for it now
				if(!empty($where)) {
					$this->order = new AdministrateWidgetCheckoutOrder($this->plugin, $where);
					if (!$this->order->exists()) {
						$this->order = false;
					}
				}

				// Was an event ID passed in?
				if(isset($_REQUEST[$this->eventKey]) && (intval($_REQUEST[$this->eventKey]) > 0)) {

					// If we've not got an order, or the order we've got doesn't match the requested key
					if(!$this->order || ($this->order->get_event_id() != $_REQUEST[$this->eventKey])) {

						// Build a new order
						$this->order = new AdministrateWidgetCheckoutOrder($this->plugin);

						// Use post/get currency, or read from settings
						if(isset($_REQUEST['order_currency'])) {
							$currency = $_REQUEST['order_currency'];
						} else {
							$currency = $this->plugin->get_option('currency', 'pricing');
						}

						// Build the new order and assign to the session
						$this->order->create($_REQUEST[$this->eventKey], session_id(), $currency);
						$_SESSION[$this->orderKey] = $this->order->get_id();
					}
				}
			}
		}

		// If we *still* don't have an order - just show the event table
		if(!$this->order) {
			return $this->plugin->display_widget('event_table');
		}

		// If this is an IPN response, handle the payment and stop
		if($current_step == constant('ADM_CHECKOUT_STEP_IPN')) {
			$log->create('Received IPN response.', $this->order->get_id());
			$this->order->receive_ipn();
			return;
		}

		// At this point, we must have an order - rejoice!
		// Read and use some defaults from the options.
		$this->_set_options();
		$this->widgetTitle = $this->_generate_title();

		// Default $errors to empty array, we'll populate it if we have any.
		$errors = array();

		/* Max step is the highest step that we've completed and
		 * validated - this determines what steps are available
		 * in the checkout widget.
		 */
		if($current_step > $this->order->get_max_step()) {

			// Set it in the post, so that we can save it with
			// the rest of the post data from the order.
			$_POST['order_max_step'] = $current_step;
		}

		if($current_step > constant('ADM_CHECKOUT_STEP_COURSE')) {
			$errors = $this->order->save($this->_prepare_fields($current_step - 1, $_POST));
		}

		// #### EXTRA VALIDATION FOR SPECIFIC STEPS ####

		// If we're in an order and there's no currency, reset to first step
		$currency = $this->order->get_currency();
		if($current_step > constant('ADM_CHECKOUT_STEP_COURSE') && empty($currency)) {
			$current_step = 1;
		}

		// If we're on the confirmation step and the T's & C's
		// weren't accepted, add the validation error.
		if($current_step == constant('ADM_CHECKOUT_STEP_CONFIRM') && !isset($_REQUEST['tac']) && !isset($_REQUEST[$this->plugin->add_namespace(array('processor', 'cancel'))])) {
			$errors[] = __('You must accept the Terms &amp; Conditions before proceeding.', 'administrate');
		}

		// If we've errors revert to previous step to allow correction.
		if($current_step > constant('ADM_CHECKOUT_STEP_COURSE') && !empty($errors)) {
			$current_step--;
		}

		// Perform logging - first work out log text.
		if($current_step == constant('ADM_CHECKOUT_STEP_PLACE')) {
			$log_message = 'Order was placed.';
		} elseif(!empty($errors)) {
			$log_message = 'Error with step #' . $current_step . '. Redisplaying.';
		} else {
			$log_message = '';
			if ($current_step > 1) {
				$log_message = 'Successfully submitted step #' . ($current_step - 1) . '. ';
			}
			$log_message .= 'Displaying step #' . $current_step . '.';
		}

		// Then update the log.
		$log->create($log_message, $this->order->get_id());

		// Form action is the 'next' step - e.g. where the 'submit' button goes.
		$form_action = $this->get_step_url($current_step + 1);

		// Run the appropriate step
		$step_content = array();
		$complete_content = false;
		if($current_step == constant('ADM_CHECKOUT_STEP_PLACE')) {

			// _run_completion will place the order with Administrate
			$complete_content = $this->_run_completion($errors);
		} else {
			if((!isset($_REQUEST['ajax']) && ($current_step >= constant('ADM_CHECKOUT_STEP_COURSE'))) || ($current_step == constant('ADM_CHECKOUT_STEP_COURSE'))) {
				$step_content[constant('ADM_CHECKOUT_STEP_COURSE')] = $this->_run_step1($errors);
			}
			if((!isset($_REQUEST['ajax']) && ($current_step >= constant('ADM_CHECKOUT_STEP_ATTENDEE'))) || ($current_step == constant('ADM_CHECKOUT_STEP_ATTENDEE'))) {
				$step_content[constant('ADM_CHECKOUT_STEP_ATTENDEE')] = $this->_run_step2($errors);
			} 
			if((!isset($_REQUEST['ajax']) && ($current_step >= constant('ADM_CHECKOUT_STEP_TERMS'))) || ($current_step == constant('ADM_CHECKOUT_STEP_TERMS'))) {
				$step_content[constant('ADM_CHECKOUT_STEP_TERMS')] = $this->_run_step3($errors);
			}
			if((!isset($_REQUEST['ajax']) && ($current_step >= constant('ADM_CHECKOUT_STEP_CONFIRM'))) || ($current_step == constant('ADM_CHECKOUT_STEP_CONFIRM'))) {
				$step_content[constant('ADM_CHECKOUT_STEP_CONFIRM')] = $this->_run_step4($errors);
			}
		}

		// If the request was made via AJAX, just spit out the requested step and exit
		if(isset($_REQUEST['ajax']) && ($current_step < constant('ADM_CHECKOUT_STEP_PLACE'))) {

			echo $this->jsonFlag;
			$json = array(
				"step"		=>	$current_step,
				"content"	=>	$step_content[$current_step]
			);
			echo json_encode($json);
			exit;

		}
		// Otherwise show the whole checkout interface
		else {

			// If there is content to display, display it in the checkout template
			if ((count($step_content) > 0) || $complete_content) {

				// Include the template
				ob_start();
				require_once($this->get_path('/views/checkout.php'));
				return ob_get_clean();

			}
		}
	}
	
	//  Run step 1
	private function _run_step1($errors = array()) {
		
		//  Includes the step 1 template
		$content = $this->get_include_contents(
			$this->get_path('/views/checkout_step1.php'),
			array(
				'course'					=>	$this->widgetTitle,
				'course_fields'				=>	$this->plugin->get_event_fields($this->order->get_event(), $this->get_option('step1_course_fields')),
				'num_attendees'				=>	$this->order->get_num_attendees(),
				'prices'					=>	$this->order->get_event_prices(),
				'pricing_basis'				=>	$this->plugin->get_option('basis', 'pricing'),
				'tax_label'					=>	$this->plugin->get_option('tax_label', 'pricing'),
				'prices_include_taxes'		=>	$this->plugin->to_boolean($this->plugin->get_option('inc_taxes', 'pricing')),
				'default_currency'			=>	$this->order->get_currency(),
				'show_currency_indicator'	=>	$this->plugin->get_option('show_currency_indicator', 'pricing'),
				'currency_indicator'		=>	$this->plugin->get_option('currency_indicator', 'pricing'),
				'event'						=>	$this->order->get_event(),
				'errors'					=>	$errors
			)
		);
		
		//  Return the content to display
		return $content;
		
	}
	
	//  Run step 2
	private function _run_step2($errors = array()) {
		
		//  Save reference to table fields
		$tableFields = $this->plugin->get_table_fields('orders');
		
		//  Set the buyer fields
		$buyerValues = $this->_get_serialized_field_values($tableFields['order_buyer_details']['serialize'], $this->order->get_buyer_details(), 'order_buyer_details_');
		
		//  Set the invoice address fields
		$invoiceValues = $this->_get_serialized_field_values($tableFields['order_invoice_address']['serialize'], $this->order->get_invoice_address(), 'order_invoice_address_');
		if (empty($invoiceValues['country'])) {
			$invoiceValues['country'] = $this->plugin->get_option('default_country', 'checkout');	
		}
		
		//  Set the attendee fields
		$attendeeValues = $this->_get_serialized_field_values($tableFields['order_attendee_details']['serialize'], $this->order->get_attendee_details(), 'order_attendee_details_', $this->order->get_num_attendees());
		
		//  Includes the step 1 template
		$content = $this->get_include_contents(
			$this->get_path('/views/checkout_step2.php'),
			array(
				'buyer_fields'		=>	$tableFields['order_buyer_details']['serialize'],
				'buyer_values'		=>	$buyerValues,
				'invoice_fields'	=>	$tableFields['order_invoice_address']['serialize'],
				'invoice_values'	=>	$invoiceValues,
				'attendee_fields'	=>	$tableFields['order_attendee_details']['serialize'],
				'attendee_values'	=>	$attendeeValues,
				'num_attendees'		=>	$this->order->get_num_attendees(),
				'countries'			=>	$this->get_countries(),
				'errors'			=>	$errors,
				'show_notes'		=> $this->plugin->get_option('show_notes_fields', 'checkout')
			)
		);
		
		//  Return the content to display
		return $content;
		
	}
	
	//  Run step 3
	private function _run_step3($errors = array()) {
		
		//  Includes the step 3 template
		$content = $this->get_include_contents(
			$this->get_path('/views/checkout_step3.php'),
			array(
				'errors'	=>	$errors
			)
		);
		
		//  Return the content to display
		return $content;
		
	}
	
	//  Run step 4
	private function _run_step4($errors = array()) {
		
		//  Save reference to table fields
		$tableFields = $this->plugin->get_table_fields('orders');
		
		//  Set the buyer fields
		$buyerValues = $this->_get_serialized_field_values($tableFields['order_buyer_details']['serialize'], $this->order->get_buyer_details(), 'order_buyer_details_');
		
		//  Set the invoice address fields
		$invoiceValues = $this->_get_serialized_field_values($tableFields['order_invoice_address']['serialize'], $this->order->get_invoice_address(), 'order_invoice_address_');
		
		//  Set the attendee fields
		$attendeeValues = $this->_get_serialized_field_values($tableFields['order_attendee_details']['serialize'], $this->order->get_attendee_details(), 'order_attendee_details_', $this->order->get_num_attendees());
		
		//  If there is a payment processor, set the order to it
		if ($this->paymentProcessor) {
			$this->paymentProcessor->set_order($this->order);	
		}
		
		//  Includes the step 4 template
		$content = $this->get_include_contents(
			$this->get_path('/views/checkout_step4.php'),
			array(
				'buyer_fields'				=>	$tableFields['order_buyer_details']['serialize'],
				'buyer_values'				=>	$buyerValues,
				'invoice_fields'			=>	$tableFields['order_invoice_address']['serialize'],
				'invoice_values'			=>	$invoiceValues,
				'attendee_fields'			=>	$tableFields['order_attendee_details']['serialize'],
				'attendee_values'			=>	$attendeeValues,
				'num_attendees'				=>	$this->order->get_num_attendees(),
				'course_fields'				=>	$this->plugin->get_event_fields($this->order->get_event(), $this->get_option('step4_course_fields')),
				'order_id'					=>	$this->order->get_id(),
				'billing_address'			=>	$this->get_option('billing_address'),
				'course'					=>	$this->widgetTitle,
				'prices'					=>	$this->order->get_event_prices(),
				'pricing_basis'				=>	$this->plugin->get_option('basis', 'pricing'),
				'tax_label'					=>	$this->plugin->get_option('tax_label', 'pricing'),
				'prices_include_taxes'		=>	$this->plugin->to_boolean($this->plugin->get_option('inc_taxes', 'pricing')),
				'default_currency'			=>	$this->order->get_currency(),
				'show_currency_indicator'	=>	$this->plugin->get_option('show_currency_indicator', 'pricing'),
				'currency_indicator'		=>	$this->plugin->get_option('currency_indicator', 'pricing'),
				'event'						=>	$this->order->get_event(),
				'errors'					=>	$errors,
				'show_notes'				=> $this->plugin->get_option('show_notes_fields', 'checkout')
			)
		);
		
		//  Return the content to display
		return $content;
		
	}
	
	//  Run step 5 (ie, completion)
	private function _run_completion($errors) {

		// If the order isn't complete, process it
		if(!$this->order->is_complete()) {

			/* If we're doing a SagePay order, there is no differentiation
			 * between SuccessURL and IPN - it only accepts one URL. For this
			 * reason we ensure that the order is processed and not submitted
			 * as 'pending.'
			 */ 
			$skip_pending = ($this->get_option('payment_processor') == 'SagePay');
			$this->order->complete($skip_pending);
		}

		//  Kill the session variable
		unset($_SESSION[$this->orderKey]);
			
		//  Includes the step 5 template
		$content = $this->get_include_contents(
			$this->get_path('/views/checkout_complete.php'),
			array(
				'payment_type'		=>	$this->order->get_payment_type(),
				'payment_processor'	=>	$this->get_option('payment_processor'),
				'errors'			=>	$errors,
				'order_num'			=>	$this->order->get_api_invoice_id()
			)
		);
			
		//  Return the content to display
		return $content;
			
	}
	
	//  Prepare fields
	private function _prepare_fields($step, $inputFields) {
		$outputFields = array();
		foreach ($inputFields as $key=>$value) {
			if (isset($this->stepFields[$step])) {
				foreach ($this->stepFields[$step] as $allowedField) {
					if (substr($key, 0, strlen($allowedField)) == $allowedField) {
						$outputFields[$key] = $value;	
					}
				}
			}
		}
		return $outputFields;
	}
	
	//  Get world countries
	public function get_countries() {
		return $this->countries;
	}
	
	//  Validate the payment
	private function _validate_payment() {
	
		//  Initialize errors array
		$errors = array();
		
		//  If the payment type is credit card, validate using processor specific routines
		if (!isset($_REQUEST['order_payment_type'])) {
			$paymentError = $this->paymentProcessor->validate_payment();
			if ($paymentError) {
				$errors['payment'] = $paymentError;
			}
		}
		
		//  Return the errors
		return $errors;
		
	}
	
	//  Process the payment
	private function _process_payment() {
		return $this->paymentProcessor->process_payment();
	}
	
	//  Get field values for a serialized set of fields
	private function _get_serialized_field_values($fields, $savedValues, $fieldPrefix, $num = false) {
		$savedValues = unserialize($savedValues);
		$values = array();
		foreach ($fields as $field) {
			if (isset($_POST[$fieldPrefix.$field])) {
				$values[$field] = $_POST[$fieldPrefix.$field];
			} else if (is_array($savedValues) && isset($savedValues[$field])) {
				$values[$field] = $savedValues[$field];
			} else {
				if ($num) {
					$values[$field] = array();
					for ($i = 1; $i <= $num; ++$i) {
						array_push($values[$field], '');
					}
				} else {
					$values[$field] = '';
				}
			}
		}
		return $values;
	}
	
	//  Generate the widget title
	private function _generate_title() {
		$title = $this->order->get_course_title() . '<br />';
		$title .= $this->plugin->format_date_span($this->order->get_event_dates());
		if($this->plugin->get_option('show_times', 'event')) {
			$title .= '<br />' . $this->plugin->format_time_span($this->order->get_event_times());
		}
		return $title;
	}
	
	//  Get the step URL
	public function get_step_url($num, $includeHost = false) {
		$href = $this->get_base_url($includeHost);
		if (strpos($href, '?') > -1) {
			$href .= '&';
		} else {
			$href .= '?';
		}
		$href .= $this->stepKey . '=' . $num;
		return $href;
	}
	
	//  Get the base URL
	public function get_base_url($includeHost = false) {
		
		//  If the base URL isn't set yet, set it now
		if (!property_exists($this, 'baseUrl')) {
			$params = $_GET;
			if (isset($params[$this->stepKey])) {
				unset($params[$this->stepKey]);	
			}
			$this->baseUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
			if (!empty($params)) {
				$this->baseUrl .= '?' . http_build_query($params);	
			}
		}
		
		//  If we're including the host ...
		if ($includeHost) {
			
			//  If the full base URL isn't set, set it now
			if (!property_exists($this, 'baseUrlFull')) {
				$tmp = 'http';

				// HTTPS can be off if the var is not set (most common) by
				// also if it's ACTUALLY set to 'off.' Handle both cases.
				if (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] !== 'off') {
					$tmp .= 's';
				}
				$tmp .= '://'.$_SERVER['SERVER_NAME'];
				if (($_SERVER["SERVER_PORT"] != 80) && ($_SERVER['SERVER_PORT'] != 443)) {
					$tmp .= ':'.$_SERVER['SERVER_PORT'];
				}
				$this->baseUrlFull = $tmp . $this->baseUrl; 
			}
			
			//  Return the full base URL
			return $this->baseUrlFull;
			
		//  Or else return the base URL
		} else {
			return $this->baseUrl;	
		}
		
	}
	
	//  Get the reset URL
	private function _get_reset_url() {
		$url = $this->get_base_url();
		if (empty($_SERVER['QUERY_STRING'])) {
			$url .= '?';
		} else {
			$url .= '&';
		}
		$url .= $this->eventKey . '=0';
		return $url;
	}
	
	//  Display the payment form
	public function display_payment_form() {
		$this->paymentProcessor->display_form();
	}
	
	//  Get the order event
	public function get_order_event() {
		return $this->order->get_event();	
	}

}
