<?php
//  Plugin config
$_ADMINISTRATE_CONFIG = array(

	//  Plugin
	'plugin' => array(
		'namespace'		=>	'administrate',
		'menu_title'	=>	'Administrate',
		'urls' 			=>	array(
			'update'	=>	'http://www.getadministrate.com/plugin/info.json',
			'docs'		=>	''
		),
		'dependencies'	=>	array(
			'php'		=>	'5',
			'jquery'	=>	'1.4.1'
		),
		'debug'			=>	false,
		'path'			=>	dirname(__FILE__)
	),
	
	//  Service
	'service' => array(
		'urls' => array(
			'demo'	=>	'https://demo.administrateapp.com/wsdl/publicxml1.6.wsdl',
			'live'	=>	'https://<domain>/wsdl/publicxml1.6.wsdl'
		),
		'cacheTime'		=>	900,
		'demoUser'		=>	'demo@getadministrate.com',
		'demoPassword'	=>	'demo',
		'tagLines'		=>	array(
			'Software for Training Providers',
			'Training Administration Software',
			'Training Administration System',
			'Training Management Software',
			'Training Management System',
			'Course Booking Software',
			'Course Management Software',
			'CRM Built for Training Providers'
		)
	),
	
	//  Options
	'options' => array(
				
		//  API options
		'api' => array(
			'title'		=>	__('API', 'administrate'),
			'intro'		=>	'',
			'fields'	=>	array(
				'mode' => array(
					'label'			=>	__('Mode', 'administrate'),
					'type'			=>	'select',
					'options'		=>	array(
						'demo'	=>	__('Demo', 'administrate'),
						'live'	=>	__('Live', 'administrate')
					),
					'required'		=>	true,
					'default'		=>	'demo'
				),
				'domain' => array(
					'label'			=>	__('Domain', 'administrate'),
					'type'			=>	'text',
					'max_length'	=>	128,
					'required'		=>	false,
					'hint'			=>	__('e.g., yoursubdomain.administrateapp.com')
				),
				'user' => array(
					'label'			=>	__('Username', 'administrate'),
					'type'			=>	'text',
					'max_length'	=>	100,
					'required'		=>	false
				),
				'password' => array(
					'label'			=>	__('Password', 'administrate'),
					'type'			=>	'password',
					'max_length'	=>	100,
					'required'		=>	false
				),
				'cache_timeout' => array(
					'label'			=>	__('Cache Time', 'administrate'),
					'type'			=>	'select',
					'options'		=>	array(
						0			=>	__('do not cache', 'administrate'),
						300			=>	__('5 minutes', 'administrate'),
						900			=>	__('15 minutes', 'administrate'),
						1800		=>	__('30 minutes', 'administrate'),
						3600		=>	__('1 hour', 'administrate'),
						21600		=>	__('6 hours', 'administrate'),
						43200		=>	__('12 hours', 'administrate'),
						86400		=>	__('24 hours', 'administrate'),
						31536000	=>	__('indefinite (until manually refreshed)', 'administrate')
					),
					'required'		=>	false,
					'default'		=>	900
				),
				'debug' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	0,
					'hint'			=>	__('Turn debug mode on. <strong>WARNING:</strong> May show warnings and notices on public site.', 'administrate')
				),
			)
		),
		
		//  General course display options
		'course' => array(
			'title'		=>	__('Courses', 'administrate'),
			'intro'		=>	'',
			'fields'	=>	array(
				'course_page' => array(
					'label'			=>	__('Course Information Page', 'administrate'),
					'type'			=>	'select',
					'options'		=>	'pages'
				),
				'course_url_structure' => array(
					'label'			=>	__('Course URL Structure', 'administrate'),
					'type'			=>	'select',
					'options'		=>	array(
						''								=>	'default',
						'category-subcategory-course'	=>	'/category/subcategory/course/',
						'category-course'				=>	'/category/course/',
						'course'						=>	'/course/'
					),
					'hint'			=>	__('IMPORTANT: Click on the "Refresh URLs" button on the "API" tab after changing this setting.', 'administrate')
				),
				'show_category_links' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Automatically show course category links on course page.', 'administrate')
				),
				'show_subcategory_links' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Automatically show course subcategory links on course page.', 'administrate')
				),
				'show_course_links' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Automatically show course links on course page.', 'administrate')
				),
				'show_course_events' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Automatically list upcoming course events on course page.', 'administrate')
				),
				'submenu_type' => array(
					'label'				=>	__('Course Submenu', 'administrate'),
					'type'				=>	'select',
					'options'			=>	array(
						'none'			=>	__('None', 'administrate'),
						'category'		=>	__('Course Categories', 'administrate'),
						'subcategory'	=>	__('Course Categories + Subcategories', 'administrate'),
						'catcourse'		=>	__('Course Categories + Courses', 'administrate'),
						'course'		=>	__('All Courses (Note: Could cause performance issues.)', 'administrate'),
					),
					'default'		=>	'none',
					'hint'			=>	__('Automatically display a submenu under the course page menu item.', 'administrate')
				),
				'show_empty_courses' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Show categories and courses even if they have no scheduled events. <strong>NOTE:</strong> Unchecking this could cause performance issues.', 'administrate')
				),
				'course_page_fields'=> array(
					'label'			=>	__('Course Fields To Show', 'administrate'),
					'type'			=>	'custom',
				),
				'page_fields' => array(
					'label'			=>	__('Fields to Show on Course Page', 'administrate'),
					'type'			=>	'multi',
					'required'		=>	false,
					'display'		=>	false
				),
				'course_field_label' => array(
					'label'			=>	__('Course Field Labels', 'administrate'),
					'type'			=>	'custom',
					'max_length'	=>	50,
					'required'		=>	true,
					'child_type'	=>	'text',
					'fields'		=>	array(
						'code'			=>	__('Course Code', 'administrate'),
						'summary'		=>	__('Summary', 'administrate'),
						'schedule'		=>	__('Schedule', 'administrate'),
						'location'		=>	__('Location', 'administrate'),
						'inclusions'	=>	__('What\'s Included', 'administrate'),
						'method'		=>	__('Instruction Method', 'administrate'),
						'prerequisites'	=>	__('Prerequisites', 'administrate'),
						'topics'		=>	__('Topics', 'administrate'),
						'benefits'		=>	__('Benefits', 'administrate'),
						'duration'		=>	__('Duration (in days)', 'administrate')
					)
				)
			)
		),
		
		//  Event options
		'event' => array(
			'title'		=>	__('Events', 'administrate'),
			'intro'		=>	'',
			'fields'	=>	array(
				'num_months' => array(
					'label'			=>	__('Default # Months to Show', 'administrate'),
					'type'			=>	'select',
					'options'		=>	array(
						3	=>	__('3', 'administrate'),
						6	=>	__('6', 'administrate'),
						9	=>	__('9', 'administrate'),
						12	=>	__('12', 'administrate')
					),
					'default'		=>	3
				),
				'show_prices' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Show prices in events table.', 'administrate')
				),
				'show_remaining_places' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Show remaining places left for events.', 'administrate')
				),
				'show_today' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Show events for the current day.', 'administrate')
				),
				'show_sold_out' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	0,
					'hint'			=>	__('Show sold out events.', 'administrate')
				),
				'translate_places_to_status' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	0,
					'hint'			=>	__('Treat events with no places left as sold out.', 'administrate')
				),
				'show_year' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	0,
					'hint'			=>	__('Include event year when showing dates.', 'administrate')
				),
				'show_times' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	0,
					'hint'			=>	__('Include event times when showing dates.', 'administrate')
				),
				'group_size' => array(
					'label'			=>	__('Group in Sets of', 'administrate'),
					'type'			=>	'select',
					'options'		=>	array(
						''	=>	__('do not group', 'administrate'),
						5	=>	__('5', 'administrate'),
						10	=>	__('10', 'administrate'),
						25	=>	__('25', 'administrate'),
						50	=>	__('50', 'administrate'),
						100	=>	__('100', 'administrate')
					),
					'default'		=>	10
				),
				'error_message' => array(
					'label'			=>	__('Error Messages', 'administrate'),
					'type'			=>	'custom',
					'max_length'	=>	100,
					'required'		=>	true,
					'child_type'	=>	'text',
					'fields'		=>	array(
						'no_events'			=>	array(
							'label'		=>	__('No Future Events', 'administrate'),	
							'default'	=>	__('We could not find any future events. This is usually a temporary error. Please try again.', 'administrate'),
							'hint'		=>	__('There are no events to display.', 'administrate')
						)
					)
				)
			)
		),
		
		//  Pricing options
		'pricing' => array(
			'title'		=>	__('Pricing', 'administrate'),
			'intro'		=>	'',
			'fields'	=>	array(
				'inc_taxes' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	0,
					'hint'			=>	__('Course prices include applicable taxes.', 'administrate')
				),
				'tax_label' => array(
					'label'			=>	'Tax Label',
					'type'			=>	'text',
					'default'		=>	'VAT',
					'required'		=>	true
				),
				'basis' => array(
					'label'			=>	__('Pricing Basis', 'administrate'),
					'type'			=>	'select',
					'options'		=>	array(
						'net'	=>	__('Net', 'administrate'),
						'gross'	=>	__('Gross', 'administrate')
					),
					'required'		=>	true,
					'default'		=>	'net'
				),
				'currency' => array(
					'label'			=>	__('Default Currency', 'administrate'),
					'type'			=>	'select',
					'options'		=>	array(
						'AED'		=>	__('AED', 'administrate'),
						'AUD'		=>	__('AUD', 'administrate'),
						'CAD'		=>	__('CAD', 'administrate'),
						'BHD'		=>	__('BHD', 'administrate'),
						'EGP'		=>	__('EGP', 'administrate'),
						'EUR'		=>	__('EUR', 'administrate'),
						'GBP'		=>	__('GBP', 'administrate'),
						'INR'		=>	__('INR', 'administrate'),
						'JOD'		=>	__('JOD', 'administrate'),
						'KWD'		=>	__('KWD', 'administrate'),
						'LBP'		=>	__('LBP', 'administrate'),
						'OMR'		=>	__('OMR', 'administrate'),
						'PLN'		=>	__('PLN', 'administrate'),
						'QAR'		=>	__('QAR', 'administrate'),
						'SAR'		=>	__('SAR', 'administrate'),
						'SYP'		=>	__('SYP', 'administrate'),
						'USD'		=>	__('USD', 'administrate'),
						'NZD'		=>	__('NZD', 'administrate')
					),
					'required'		=>	true,
					'default'		=>	'GBP'
				),
				'currency_indicator'	=>	array(
					'label'			=>	__('Currency Indicator', 'administrate'),
					'type'			=>	'select',
					'options'		=>	array(
						'symbol'	=>	__('symbol (e.g., £)', 'administrate'),
						'currency'	=>	__('currency (e.g., GBP)', 'administrate')
					),
					'default'		=>	'symbol'
				),
				'show_currency_indicator' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Show currency indicators with prices.', 'administrate')
				)
			)
		),
		
		//  Checkout widget options
		'checkout' => array(
			'title'		=>	__('Registration', 'administrate'),
			'intro'		=>	'',
			'fields'	=>	array(
				'checkout_page' => array(
					'label'			=>	__('Event Registration Page', 'administrate'),
					'type'			=>	'select',
					'options'		=>	'pages'
				),
				'show_submenu' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	0,
					'hint'			=>	__('Automatically show events under event registration page in menu.', 'administrate'),
				),
				'force_email_check' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Always require and check uniqueness of attendee emails.', 'administrate'),
				),
				'show_notes_fields' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	0,
					'hint'			=>	__('Show \'notes\' fields, allowing customers to input extra information about their delegates.', 'administrate'),
				),/*
				'order_notify_email'=> array(
					'label'			=>	'Order Notify Email',  //  Use hint for checkboxes
					'type'			=>	'text',
					'hint'			=>	__('Email address to receive order notifications (unless otherwise specified by payment processor).', 'administrate')
				),*/
				'max_attendees' => array(
					'label'			=>	__('Max Attendees per Order', 'administrate'),
					'type'			=>	'select',
					'options'		=>	array(
						0	=>	__('# places remaining for event', 'administrate'),
						1	=>	__('1', 'administrate'),
						2	=>	__('2', 'administrate'),
						3	=>	__('3', 'administrate'),
						4	=>	__('4', 'administrate'),
						5	=>	__('5', 'administrate'),
						6	=>	__('6', 'administrate'),
						7	=>	__('7', 'administrate'),
						8	=>	__('8', 'administrate'),
						9	=>	__('9', 'administrate'),
						10	=>	__('10', 'administrate')
					),
					'default'		=>	0
				),
				'default_country' => array(
					'label'			=>	__('Default Invoice Country', 'administrate'),
					'type'			=>	'select',
					'options'		=>	'countries'
				),
				'terms' => array(
					'label'			=>	__('Terms &amp; Conditions', 'administrate'),
					'type'			=>	'textarea',
					'required'		=>	true,
					'default'		=>	__('Terms & Conditions go here', 'administrate'),
					'height'		=>	15,
					'allow_html'	=>	true
				),
				'course_fields'		=> array(
					'label'			=>	__('Course Fields', 'administrate'),
					'type'			=>	'custom',
				),
				'step1_course_fields' => array(
					'label'			=>	__('Fields to Show on Step 1', 'administrate'),
					'type'			=>	'multi',
					'required'		=>	false,
					'display'		=>	false
				),
				'step4_course_fields' => array(
					'label'			=>	__('Fields to Show on Step 4', 'administrate'),
					'type'			=>	'multi',
					'required'		=>	false,
					'display'		=>	false
				),
				'billing_address' => array(
					'label'			=>	__('Billing Address', 'administrate'),
					'type'			=>	'textarea',
					'required'		=>	true,
					'default'		=>	__('Your billing address goes here', 'administrate')
				),
				'payment_by_invoice' => array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Accept payment by invoice.', 'administrate')
				),
				'invoice_instructions' => array(
					'label'			=>	__('Invoice Instructions', 'administrate'),
					'type'			=>	'textarea',
					'default'		=>	__('You will be e-mailed an invoice for the selected course. You will not receive a registration confirmation with course details until your payment has been received and processed by our office. If the course you have selected is in 6 or less days we will contact you for further instructions. If the course you selected is in 7 or more days please mail payment to:', 'administrate'),
					'required'		=>	false
				),
				'invoice_send_email'=> array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	1,
					'hint'			=>	__('Send the user an email when paying by invoice.', 'administrate')
				),
				'payment_by_cc'		=>	array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	0,
					'hint'			=>	__('Accept payment by credit card (requires 3rd party payment processor).', 'administrate')
				),
				'payment_processor' => array(
					'label'			=>	__('Payment Processor', 'administrate'),
					'type'			=>	'select',
					'options'		=>	array(
						'PayPal'		=>	__('PayPal', 'administrate'),
						'SagePay'		=>	__('Sagepay', 'administrate')/*,
						'AuthorizeNet'	=>	__('Authorize.NET', 'administrate')*/
					),
					'default'		=>	'PayPal'
				),
				'paypal_mode' => array(
					'label'			=>	__('PayPal Mode?', 'administrate'),
					'type'			=>	'select',
					'options'		=> 	array(
						'Live'		=>	__('Live', 'administrate'),
						'Sandbox'	=>	__('Sandbox', 'administrate')
					),
					'default'		=> 'Live'
				),
				'paypal_user' => array(
					'label'			=>	__('PayPal API User', 'administrate'),
					'type'			=>	'text',
					'max_length'	=>	100
				),
				'paypal_password' => array(
					'label'			=>	__('PayPal API Password', 'administrate'),
					'type'			=>	'password',
					'max_length'	=>	100
				),
				'paypal_signature' => array(
					'label'			=>	__('PayPal API Signature', 'administrate'),
					'type'			=>	'text',
					'max_length'	=>	100
				),
				'sagepay_mode' => array(
					'label'			=>	__('Sagepay Mode?', 'administrate'),
					'type'			=>	'select',
					'options'		=> 	array(
						'Live'		=>	__('Live', 'administrate'),
						'Test'		=>	__('Test', 'administrate')
					),
					'default'		=> 'Live'
				),
				'sagepay_vendor_username' => array(
					'label'			=>	__('SagePay Vendor Username', 'administrate'),
					'type'			=>	'text',
					'max_length'	=>	100
				),
				'sagepay_encryption_password' => array(
					'label'			=>	__('SagePay Encryption Password', 'administrate'),
					'type'			=>	'text',
					'max_length'	=>	100
				),
				'sagepay_email_message' => array(
					'label'			=>	__('SagePay Email Message', 'administrate'),
					'type'			=>	'textarea',
					'default'		=>	__('Thank you for your order.', 'administrate'),
					'required'		=>	false
				),/*
				'authorizenet_user' => array(
					'label'			=>	__('Authorize.NET Login ID', 'administrate'),
					'type'			=>	'text',
					'max_length'	=>	100
				),
				'authorizenet_key' => array(
					'label'			=>	__('Authorize.NET API Key', 'administrate'),
					'type'			=>	'text',
					'max_length'	=>	100
				),*/
				'cc_instructions' => array(
					'label'			=>	__('Credit Card Instructions', 'administrate'),
					'type'			=>	'textarea',
					'default'		=>	__('Enter the instructions for payment by credit card here', 'administrate'),
					'required'		=>	false
				),
				'cc_send_email'=> array(
					'label'			=>	'',  //  Use hint for checkboxes
					'type'			=>	'check',
					'default'		=>	0,
					'hint'			=>	__('Send the user an email when paying by credit card.', 'administrate')
				),
				'conversion_tracking' => array(
					'label'			=>	__('Conversion Tracking Snippet(s)', 'administrate'),
					'type'			=>	'textarea',
					'required'		=>	false,
					'height'		=>	10,
					'allow_html'	=>	true
				),
				'error_message' => array(
					'label'			=>	__('Error Messages', 'administrate'),
					'type'			=>	'custom',
					'max_length'	=>	100,
					'required'		=>	true,
					'child_type'	=>	'text',
					'fields'		=>	array(
						'general'			=>	array(
							'label'		=>	__('General', 'administrate'),	
							'default'	=>	__('Apologies, we have encountered an error. Please try again and if this problem reoccurs, please contact us for assistance quoting the following message:', 'administrate'),
							'hint'		=>	__('An unspecified error occurred.', 'administrate')
						),
						'session_timeout'	=>	array(
							'label'		=>	__('Session Timeout', 'administrate'),	
							'default'	=>	__('Either you have already placed this order, or your session has timed out. Please feel free to start the order process again.', 'administrate'),
							'hint'		=>	__('The user\' session has expired.', 'administrate')
						),
						'course_error'	=>	array(
							'label'		=>	__('Course Error', 'administrate'),	
							'default'	=>	__('There was a problem processing the course you have requested. This is usually a temporary error. Please try again.', 'administrate'),
							'hint'		=>	__('The service can\'t get the course information.', 'administrate')
						),
						'invalid_discount'	=>	array(
							'label'		=>	__('Invalid Discount Code', 'administrate'),	
							'default'	=>	__('Sorry, the discount code you entered is not valid. Please try again.', 'administrate'),
							'hint'		=>	__('An invalid discount code was entered.', 'administrate')
						),
						'incomplete_fields'	=>	array(
							'label'		=>	__('Incomplete Fields', 'administrate'),	
							'default'	=>	__('Please be sure to complete all fields.', 'administrate'),
							'hint'		=>	__('A user failed to fill out all required inputs.', 'administrate')
						),
						'duplicate_email'	=>	array(
							'label'		=>	__('Duplicate Email', 'administrate'),	
							'default'	=>	__('All attendees MUST have unique email addresses.  Please correct this and continue.', 'administrate'),
							'hint'		=>	__('Two or more attendees have the same email address.', 'administrate')
						)
					)
				)
			)
		)
		
	),
	
	//  Database fields
	'data_labels' => array(
	
		//  Orders table
		'orders' => array(		
			'order_status'					=>	__('Order Status', 'administrate'),
			'order_payment_type'			=>	__('Payment Type', 'administrate'),
			'order_time_started'			=>	__('Datetime Started', 'administrate'),
			'order_time_completed'			=>	__('Datetime Completed', 'administrate'),
			'order_event_id'				=>	__('Event', 'administrate'),
			'order_session_id'				=>	__('Session ID', 'administrate'),
			'order_max_step'				=>	__('Last Step', 'administrate'),
			'order_currency'				=>	__('Currency', 'administrate'),
			'order_discount'				=>	__('Discount Code', 'administrate'),
			'order_buyer_details'			=>	array(
				'first_name'	=>	__('First Name', 'administrate'),
				'last_name'		=>	__('Last Name', 'administrate'),
				'company'		=>	__('Company', 'administrate'),
				'email'			=>	__('Email', 'administrate'),
				'phone'			=>	__('Phone', 'administrate'),
				'notes'			=>	__('Order Notes', 'administrate')
			),
			'order_invoice_address'			=>	array(
				'address'		=>	__('Address', 'administrate'),
				'city'			=>	__('City', 'administrate'),
				'territory'		=>	__('County/State', 'administrate'),
				'postal_code'	=>	__('Postal Code', 'administrate'),
				'country'		=>	__('Country', 'administrate')
			),
			'order_num_attendees'			=>	__('# Attendees', 'administrate'),
			'order_attendee_details'		=> array(
				'first_name'	=>	__('First Name', 'administrate'),
				'last_name'		=>	__('Last Name', 'administrate'),
				'email'			=>	__('Email', 'administrate'),
				'notes'			=>	__('Order Notes', 'administrate')
			),
			'order_processor_transaction_id'	=>	__('Processor Transaction ID', 'administrate'),
			'order_api_invoice_id'				=>	__('API Order ID', 'adminsitrate')
		),
		
		//  Logs table
		'logs' => array(
			'log_msg'		=>	__('Log Message', 'administrate'),
			'log_time'		=>	__('Log Datetime', 'administrate'),
			'log_order_id'	=>	__('Log Order ID', 'administrate')
		)
	
	),
	
	//  Additional admin pages
	'pages' => array(
		'seo'	=>	'SEO',
		'logs'	=>	'Logs',
		'ref'	=>	'Reference',
		'help'	=>	'Help',
		'debug'	=>	'Debug'
	)

);
