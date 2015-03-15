<?php
//  Administrate checkout widget order
class AdministrateWidgetCheckoutOrder extends WPPluginPatternDAO {
	
	//  Properties
	protected $tableKey = 'orders';
	protected $primaryKey = 'order_id';
	
	//  Constructor
	public function __construct(&$plugin, $fields = array()) {
	
		//  Call parent constructor
		parent::__construct($plugin, $fields);
		
	}
	
	//  Create a new order
	public function create($event_id, $session_id, $currency = false) {
		if (!$currency) {
			$currency = $this->plugin->get_option('currency', 'pricing');	
		}
		return parent::create(array(
			'order_session_id'		=>	$session_id,
			'order_event_id'		=>	$event_id,
			'order_currency'		=>	$currency,
			'order_time_started'	=>	time()
		));
	}

	public function receive_ipn() {

		// If we don't have an invoice ID, then the user hasn't yet
		// visited step 5 - so simply process the order.
		if(!$this->get_api_invoice_id()) {
			$this->complete(true);
		} else {

			// Tell Administrate that the order is now complete
			AdministrateAPI::make_soap_call('completeOrder', $this->get_api_invoice_id());

			// And ensure we update the status of the order in the plugin DB.
			$fields = array(
				'order_time_completed'				=>	time(),
				'order_status'						=>	'C',
				'order_payment_type'				=>	'C',
				'order_api_invoice_id'				=>	intval($this->get_api_invoice_id())
			);

			// Save the order
			$this->save($fields);
		}

		$this->plugin->db->query('UPDATE wp_administrate_orders SET order_being_processed = 0 WHERE order_id = ' . $this->get_id() . ';');

	}

	//  Complete the order
	public function complete($cc_straight_to_placed = false) {

		// Lock tables to mark order as being processed, so that we don't
		// process two orders at the same time.
		// By default, presume we cannot process.
		$can_process = 0;
		try {
			$this->plugin->db->query('LOCK TABLE wp_administrate_orders WRITE');

			$processed = $this->plugin->db->get_row(
				'SELECT order_being_processed FROM wp_administrate_orders WHERE order_id = ' . $this->get_id() . ';',
				'ARRAY_A'
			);

			// Order is not being processed, so let's mark that we can.
			if($processed['order_being_processed'] == 0) {
				$this->plugin->db->query('UPDATE wp_administrate_orders SET order_being_processed = 1 WHERE order_id = ' . $this->get_id() . ';');
				$can_process = 1;
			}

			$this->plugin->db->query('UNLOCK TABLES;');
		} catch (Exception $e) {

			$this->plugin->db->query('UNLOCK TABLES;');
			throw $e;
		}

		//  Only process if the order is not already complete
		if (!$this->is_complete() && $can_process == 1) {

			//  Initialize the API
			$this->plugin->init_api();
			
			//  Set the payment type
			$paymentType = 'I';
			if (!isset($_REQUEST['order_payment_type'])) {
				$paymentType = 'C';
			}
		
			//  Try to get the user with email address
			$user = $this->plugin->make_api_call('get_user_by_email', $this->get_buyer_email(), true);
			
			//  If the user exists, get the existing delegates and initialize objects
			if ($user->exists()) {
				$delegates = $user->get_delegates();
				
			//  Otherwise add the user	
			} else {
				$fields = array(
					'is_individual'	=>	(strlen(trim($this->get_buyer_company())) == 0),
					'email'			=>	$this->get_buyer_email(),
					'no_contact'	=>	true,
					'company'		=>	$this->get_buyer_company(),
					'first_name'	=>	$this->get_buyer_first_name(),
					'last_name'		=>	$this->get_buyer_last_name(),
					'phone'			=>	$this->get_buyer_phone(),
					'address1'		=>	$this->get_invoice_street(),
					'city'			=>	$this->get_invoice_city(),
					'territory'		=>	$this->get_invoice_territory(),
					'postal_code'	=>	$this->get_invoice_postal_code(),
					'country'		=>	$this->get_invoice_country(),
					'password'		=>	null
				);
				$user = AdministrateAPI::add_user($fields);
				$delegates = array();
			}

			//  Loop through attendees and add any that aren't already delegates
			$attendees = $this->get_attendees();
			for ($i = 0, $numAttendees = count($attendees); $i < $numAttendees; $i++) {
				
				//  If the delegate doesn't exist, add it
				$delegate = $user->get_delegate_by_email($attendees[$i]['email']);
				if (!$delegate) {
					$delegate = $user->add_delegate(array(
						'email'			=>	$attendees[$i]['email'],
						'first_name'	=>	$attendees[$i]['first_name'],
						'last_name'		=>	$attendees[$i]['last_name'],
						'notes'			=>	$attendees[$i]['notes']
					));
				}
				
				//  Add the delegate ID to the attendees array for later reference
				$attendees[$i]['delegate_id'] = $delegate->get_id();
				
			}
			
			//  Loop through attendees and add them as cart entries
			$cartEntries = array();
			$delegateEntryObj = new AdministrateAPIDelegateCartEntry();
			foreach ($attendees as $attendee) {
				array_push(
					$cartEntries, 
					array(
						'event_id'		=>	$this->get_event_id(),
						'delegate_id'	=>	$attendee['delegate_id'],
						'email'			=>	$attendee['email'],
						'company'		=>	$this->get_buyer_company(),
						'first_name'	=>	$attendee['first_name'],
						'last_name'		=>	$attendee['last_name'],
						'notes'			=>	$attendee['notes']
					)
				);
			}

			/* Set the order details. We set is_pending if it's a credit
			 * card payment and we're not forcing it straight to placed.
			 */
			$orderDetails = array(
				'is_pending'		=>	($paymentType == 'C' && !$cc_straight_to_placed),
				'date'				=>	time(),
				'user_id'			=>	$user->get_id(),
				'payment_method'	=>	$paymentType,
				'currency'			=>	$this->get_currency(),
				'region'			=>	$this->get_event_region_id(),
				'email'				=>	$this->get_buyer_email(),
				'company'			=>	$this->get_buyer_company(),
				'first_name'		=>	$this->get_buyer_first_name(),
				'last_name'			=>	$this->get_buyer_last_name(),
				'phone'				=>	$this->get_buyer_phone(),
				'address1'			=>	$this->get_invoice_street(),
				'city'				=>	$this->get_invoice_city(),
				'territory'			=>	$this->get_invoice_territory(),
				'postal_code'		=>	$this->get_invoice_postal_code(),
				'country'			=>	$this->get_invoice_country(),
				'notes'				=>	$this->get_buyer_notes()
			);
			
			//  Create the order object the API
			$orderObj = array(
				'order_details'		=>	$orderDetails,
				'cart_entries'		=>	$cartEntries
			);
			
			// If this is not a PayPal IPN response, submit the order.
			if(!($paymentType == 'C' && $this->get_status() == 'P')) {
				$orderNum = AdministrateAPI::submit_order($orderObj);
			}
			
			//  Send email the invoice
			$sendEmail = false;
			if ($paymentType == 'I') {
				$sendEmail = $this->plugin->to_boolean($this->plugin->get_option('invoice_send_email', 'checkout'));

			// If this is not a PayPal IPN response, submit the email.
			} elseif ($paymentType == 'C' && $this->get_status() == 'I') {
				$sendEmail = $this->plugin->to_boolean($this->plugin->get_option('cc_send_email', 'checkout'));
			}

			if ($sendEmail) {
				$emailSent = AdministrateAPI::send_invoice($orderNum);
			}
			
			//  Mark the order as complete in the database
			$orderStatus = 'C';

			// If it's a CC payment and we're not forcing it straight to 'placed,'
			// ensure it is set to pending.
			if ($paymentType == 'C' && !$cc_straight_to_placed) {
				$orderStatus = 'P';
			}
			$fields = array(
				'order_time_completed'				=>	time(),
				'order_status'						=>	$orderStatus,
				'order_payment_type'				=>	$paymentType,
				'order_api_invoice_id'				=>	intval($orderNum),
				'order_being_processed'				=> 	0
			);
		
			//  Save the order
			$result = $this->save($fields);

			//  Return the order number
			return $orderNum;
			
		//  Or if the order is already complete, or is already processing
		} else {

			// Wait for up to 10 seconds to see if this order is already being
			// processed - we still want to return an order id if we can.
			if($can_process == 0) {
				$timeout = 1;
				$complete = 0;
				while(!$complete || $timeout <= 10) {
					$completed = $this->plugin->db->get_row(
						'SELECT order_status FROM wp_administrate_orders WHERE order_id = ' . $this->get_id() . ';',
						'ARRAY_A'
					);
					$complete = ($completed['order_status'] == 'C');
					$timeout++;
					sleep(1);
				}

				// If it's still being processed, log an error and return
				if(!$complete) {
					error_log('Couldn\'t retrieve completed order.');
					return false;
				}
			}

			return $this->get_api_invoice_id();
			
		}
		
	}

	//  Validate fields
	protected function _validate_fields($fields = array()) {
	
		//  Loop through field definitions
		$errors = array();
		$delegateEmails = array();
		$check_attendee_email = ($this->plugin->get_option('force_email_check', 'checkout') == 1);
		foreach ($this->fieldDefinitions as $field=>$properties) {
			if (isset($fields[$field])) {
				
				//  If this field has serialized subvalues, check them
				if (isset($properties['serialize'])) {
					
					//  Loop through serialized fields and add an error for anything empty
					foreach ($properties['serialize'] as $key=>$subfield) {

						if($subfield == 'notes') {
							continue;
						}

						$subfieldKey = $field . $this->plugin->get_key_delimiter() . $subfield;
						if (is_array($fields[$subfieldKey])) {
							foreach ($fields[$subfieldKey] as $multiField) {
								$is_attendee_email_field = (($field == 'order_attendee_details') && ($subfield == 'email'));

								// Validation specific to the attendee email field
								if($is_attendee_email_field) {

									if(!$check_attendee_email && empty($multiField)) {

										// If we're not checking these, and it's empty, it's fine - just skip
										continue;
									} else {

										// If it has a value, we must ensure it's valid.
										if(!filter_var($multiField, FILTER_VALIDATE_EMAIL)) {
											$errors[$subfieldKey.$multiField] = $multiField . __(' is not a valid email address.', 'administrate');
										}
									}
								}

								// If check if the field is empty, and throw validation warning
								if (empty($multiField)) {
									$errors[$subfieldKey] = $this->plugin->get_data_label($this->tableKey, $field, $subfield) . __(' field is required.', 'administrate');
								}

								// If it's an attendee email field, check it's unique.
								if ($is_attendee_email_field) {
									if (in_array($multiField, $delegateEmails)) {
										$errors[$field] = __('All attendees must have a unique email address.', 'administrate');
									} else {
										array_push($delegateEmails, $multiField);
									}
								}

							}
						} else {

							// Don't validate company, this is to accommodate 'individuals.'
							if($subfield == 'company') {
								continue;
							}

							// Check empty fields.
							if(empty($fields[$subfieldKey])) {
								$errors[$subfieldKey] = $this->plugin->get_data_label($this->tableKey, $field, $subfield) . __(' field is required.', 'administrate');
							}

							// If this is an email field, ensure it is a valid email
							if($subfield == 'email') {
								if(!filter_var($fields[$subfieldKey], FILTER_VALIDATE_EMAIL)) {
									$errors[$subfieldKey] = $fields[$subfieldKey] . __(' is not a valid email address.', 'administrate');
								}
							}

						}
					}
					
				//  Or else just check the field
				} else {
					if (isset($properties['null']) && !$properties['null'] && empty($fields[$field])) {
						$errors[$field] = $this->plugin->get_data_label($this->tableKey, $field) . __(' field is required.', 'administrate');
					}
				}
				
			}
			
		}
		
		//  Return the errors
		if (empty($errors)) {
			return false;	
		} else {
			return $errors;
		}
		
	}
	
	//  Get the max step
	public function get_max_step() {
		return $this->row['order_max_step'];	
	}
	
	//  Get the session ID
	public function get_session_id() {
		return $this->row['order_session_id'];	
	}
	
	//  Get the payment type
	public function get_payment_type() {
		return $this->row['order_payment_type'];	
	}
	
	//  Get the order status
	public function get_status() {
		return $this->row['order_status'];	
	}
	
	//  Whether or not the order is complete
	public function is_complete() {
		return ($this->get_status() == 'C');	
	}
	
	//  Get the order currency
	public function get_currency() {
		return $this->row['order_currency'];	
	}
	
	//  Get the time started
	public function get_time_started() {
		return $this->row['order_time_started'];	
	}
	
	//  Get the time completed
	public function get_time_completed() {
		return $this->row['order_time_completed'];	
	}

	public function get_is_being_processed() {
		return ($this->row['order_being_processed'] == 1);
	}

	/* **** BUYER DETAILS *** */
	
	//  Get the buyer details
	public function get_buyer_details() {
		return $this->row['order_buyer_details'];	
	}
	
	//  Get the buyer details fields
	public function get_buyer_details_fields() {
		$this->_set_buyer_details_fields();
		return $this->buyerDetails;	
	}
	
	//  Set buyer details
	private function _set_buyer_details_fields() {
		if (!property_exists($this, 'buyerDetails')) {
			$this->buyerDetails = unserialize($this->row['order_buyer_details']);
		}	
	}
	
	//  Get buyer field
	private function _get_buyer_field($field) {
		$this->_set_buyer_details_fields();
		return $this->buyerDetails[$field];
	}
	
	//  Get the buyer email
	public function get_buyer_email() {
		return $this->_get_buyer_field('email');
	}
	
	//  Get the buyer first name
	public function get_buyer_first_name() {
		return $this->_get_buyer_field('first_name');
	}
	
	//  Get the buyer last name
	public function get_buyer_last_name() {
		return $this->_get_buyer_field('last_name');
	}
	
	//  Get the buyer phone
	public function get_buyer_phone() {
		return $this->_get_buyer_field('phone');
	}
	
	//  Get the buyer compnay
	public function get_buyer_company() {
		return $this->_get_buyer_field('company');
	}

	public function get_buyer_notes() {
		return $this->_get_buyer_field('notes');
	}
	
	/* **** INVOICE ADDRESS *** */
	
	//  Get the invoice address
	public function get_invoice_address() {
		return $this->row['order_invoice_address'];	
	}
	
	//  Get the invoice address
	public function get_invoice_address_fields() {
		$this->_set_invoice_address_fields();
		return $this->invoiceAddress;	
	}
	
	//  Set invoice address
	private function _set_invoice_address_fields() {
		if (!property_exists($this, 'invoiceAddress')) {
			$this->invoiceAddress = unserialize($this->get_invoice_address());
		}	
	}
	
	//  Get invoice field
	private function _get_invoice_field($field) {
		$this->_set_invoice_address_fields();
		return $this->invoiceAddress[$field];
	}
	
	//  Get the invoice address
	public function get_invoice_street() {
		return $this->_get_invoice_field('address');
	}
	
	//  Get the invoice city
	public function get_invoice_city() {
		return $this->_get_invoice_field('city');
	}
	
	//  Get the invoice territory
	public function get_invoice_territory() {
		return $this->_get_invoice_field('territory');
	}
	
	//  Get the invoice postal code
	public function get_invoice_postal_code() {
		return $this->_get_invoice_field('postal_code');
	}

	//  Get the invoice country
	public function get_invoice_country() {
		return $this->_get_invoice_field('country');
	}
	
	/* **** ATTENDEE DETAILS *** */
	
	//  Get the number of attendees
	public function get_num_attendees() {
		return $this->row['order_num_attendees'];	
	}
	
	//  Get the attendees
	public function get_attendee_details() {
		return $this->row['order_attendee_details'];
	}
	
	//  Get attendees
	public function get_attendees() {
		if (!property_exists($this, 'attendeeDetails')) {
			$tmp = unserialize($this->row['order_attendee_details']);
			$this->attendeeDetails = array();
			for ($i = 0, $numAttendees = count($tmp['first_name']); $i < $numAttendees; ++$i) {
				array_push(
					$this->attendeeDetails, 
					array(
						'first_name'	=>	$tmp['first_name'][$i],
						'last_name'		=>	$tmp['last_name'][$i],
						'email'			=>	$tmp['email'][$i],
						'notes'			=>	$tmp['notes'][$i]
					)
				);	
			}
		}
		return $this->attendeeDetails;
	}
	
	/* **** Payment Details *** */
	
	//  Get Processor transaction ID
	public function get_processor_transaction_id() {
		return $this->row['order_processor_transaction_id'];	
	}
	
	//  Set the processor transaction ID
	public function set_processor_transaction_id($txnId) {
		$orderNum = $this->complete(); 
		$fields = array(
			'order_status'						=>	'C',
			'order_processor_transaction_id'	=>	$txnId
		);
		$result = $this->save($fields);	
		return $orderNum;
	}
	
	//  Whether payment is complete
	public function payment_already_processed() {
		$txnId = $this->get_processor_transaction_id();
		return !empty($txnId);	
	}
	
	/* **** EVENT *** */
	
	//  Get the devent
	public function get_event() {
		$this->_set_event();
		return $this->event;	
	}
	
	//  Get the event ID
	public function get_event_id() {
		return $this->row['order_event_id'];	
	}
	
	//  Get the event dates
	public function get_event_dates() {
		$this->_set_event();
		return $this->event->get_dates();
	}

	//  Get the event dates
	public function get_event_times() {
		$this->_set_event();
		return $this->event->get_times();
	}

	//  Get the event location
	public function get_event_location() {
		$this->_set_event();
		return $this->event->get_location();	
	}
	
	//  Get the event location region
	public function get_event_region_id() {
		$this->_set_event();
		return $this->event->get_region_id();	
	}
	
	//  Get the event prices
	public function get_event_prices() {
		$this->_set_event();
		return $this->event->get_prices();	
	}
	
	//  Get the event price based on order currency
	public function get_event_price() {
		$this->_set_event();
		if (!property_exists($this, 'eventPrice')) {
			$this->eventPrice = $this->event->get_price_by_currency($this->get_currency());	
		}
		return $this->eventPrice;
	}
	
	//  Get the event price based on order currency
	public function get_event_gross_price() {
		$this->_set_event();
		if (!property_exists($this, 'eventPrice')) {
			$this->get_event_price();
		}
		if(is_object($this->eventPrice)) {
			return $this->eventPrice->get_gross();
		}
		return null;
	}
	
	//  Get the event price based on order currency
	public function get_event_net_price() {
		$this->_set_event();
		if (!property_exists($this, 'eventPrice')) {
			$this->get_event_price();
		}
		if(is_object($this->eventPrice)) {
			return $this->eventPrice->get_net();
		}
		return null;
	}
	
	//  Get the event default currency
	public function get_event_default_currency() {
		$this->_set_event();
		return $this->event->get_default_currency();	
	}
	
	//  Get the event object from the API
	private function _set_event() {
		if (!property_exists($this, 'event')) {
			$this->event = $this->plugin->make_api_call('get_event', $this->get_event_id());	
		}
	}
	
	/* **** COURSE *** */
	
	//  Get the course code
	public function get_course_code() {
		$this->_set_course();
		return $this->course->get_code();	
	}
	
	//  Get the course title
	public function get_course_title() {
		$this->_set_course();
		return $this->course->get_title();	
	}
	
	//  Get the course summary
	public function get_course_summary() {
		$this->_set_course();
		return $this->course->get_summary();	
	}
	
	//  Get the course schedule
	public function get_course_schedule() {
		$this->_set_course();
		return $this->course->get_schedule();	
	}
	
	//  Get the course inclusions
	public function get_course_inclusions() {
		$this->_set_course();
		return $this->course->get_inclusions();	
	}
	
	//  Get the course method
	public function get_course_method() {
		$this->_set_course();
		return $this->course->get_method();	
	}
	
	//  Get the course prerequisites
	public function get_course_prerequisites() {
		$this->_set_course();
		return $this->course->get_prerequisites();	
	}
	
	//  Get the course topics
	public function get_course_topics() {
		$this->_set_course();
		return $this->course->get_topics();	
	}
	
	//  Get the course benefits
	public function get_course_benefits() {
		$this->_set_course();
		return $this->course->get_benefits();	
	}
	
	//  Get the course duration
	public function get_course_duration() {
		$this->_set_course();
		return $this->course->get_duration();	
	}
	
	//  Set the course
	private function _set_course() {
		$this->_set_event();
		if (!property_exists($this, 'course')) {
			$this->course = $this->plugin->make_api_call('get_course_by_code', $this->event->get_course_code());
		}
	}
	
	/* **** API *** */
	
	//  Get the API invoice ID
	public function get_api_invoice_id() {
		return $this->row['order_api_invoice_id'];	
	}
	
	/* **** LOGS *** */
	
	//  Get the order logs
	public function get_logs() {
		require_once($this->plugin->get_path('/widgets/checkout/AdministrateWidgetCheckoutLog.php'));
		$where = array(
			'log_order_id'	=>	$this->get_id()
		);
		$order = array(
			'log_time'	=>	'ASC'
		);
		$dao = new AdministrateWidgetCheckoutLog($this->plugin);
		return $dao->get_all($where, $order);	
	}
	
	
}
