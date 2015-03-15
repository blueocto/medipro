<?php
//  Administrate API User
class AdministrateAPIUser extends AdministrateAPIObject {
	
	//  Properties
	protected $fields = array(
		'id'			=> array(
			'api_field'	=>	'WebsiteUserID'
		),
		'is_individual'	=> array(
			'api_field'	=>	'IsIndividual',
			'default'	=>	0,
			'required'	=>	true
		),
		'email'	=> array(
			'api_field'	=>	'Email',
			'required'	=>	true
		),
		'no_contact'	=> array(
			'api_field'	=>	'DontMail',
			'default'	=>	0,
			'required'	=>	true
		),
		'password'	=> array(
			'api_field'	=>	'Password'
		),
		'company'	=> array(
			'api_field'	=>	'Company',
			'required'	=>	true
		),
		'first_name'	=> array(
			'api_field'	=>	'FirstName',
			'required'	=>	true
		),
		'last_name'	=> array(
			'api_field'	=>	'LastName',
			'required'	=>	true
		),
		'title'	=> array(
			'api_field'	=>	'JobTitle'
		),
		'department'	=> array(
			'api_field'	=>	'Department'
		),
		'address1'	=> array(
			'api_field'	=>	'Address1'
		),
		'address2'	=> array(
			'api_field'	=>	'Address2',
		),
		'address3'	=> array(
			'api_field'	=>	'Address3'
		),
		'city'	=> array(
			'api_field'	=>	'City'
		),
		'territory'	=> array(
			'api_field'	=>	'County'
		),
		'postal_code'	=> array(
			'api_field'	=>	'PostCode'
		),
		'country'	=> array(
			'api_field'	=>	'CountryCode'
		),
		'phone'	=> array(
			'api_field'	=>	'Tel'
		),
		'mobile'	=> array(
			'api_field'	=>	'Mobile'
		),
		'default_currency'	=> array(
			'api_field'	=>	'DefaultCurrency'
		),
		'referred_by'	=> array(
			'api_field'	=>	'HearAboutUs'
		),
		'referred_by_text'	=> array(
			'api_field'	=>	'HearAboutUsText'
		),
		'contact_id'	=> array(
			'api_field'	=>	'ContactID'
		),
		'custom_date1'	=> array(
			'api_field'	=>	'CustomDate1'
		),
		'custom_date2'	=> array(
			'api_field'	=>	'CustomDate2'
		),
		'custom_dropdown'	=> array(
			'api_field'	=>	'CustomDropDown1'
		),
		'delegates'	=> array(
			'api_field'	=>	'Delegates'
		)
	);
	
	//  Construct
	public function __construct($obj = false) {
		
		//  Call the parent object
		parent::__construct($obj);
		
		//  If an ID was passed, query for the user
		if (is_numeric($obj)) {
			try {
				AdministrateAPI::log('Found user ID' . $obj);	
				return AdministrateAPI::get_data('getUser', $this->prepare_fields(array('id'=>$fields)));
			} catch (Exception $e) {
				AdministrateAPI::log('Can\'t find user ID ' . $obj, 'warning');
				return false;		
			}
		}
		
	}
	
	//  Get a user by email address
	public function get_by_email($email) {
		try {
			AdministrateAPI::log('Get user by email ' . $email);
			$fields = array(
				'email'	=>	$email
			);
			return new AdministrateAPIUser(AdministrateAPI::make_soap_call('getUserByEmail', array($email)));
		} catch(Exception $e) {
			AdministrateAPI::log('Error getting user by email: '.$e->getMessage(), 'warning');
			return false;
		}	
	}
	
	//  Add a user
	public function add($fields = array()) {
		if (!$this->fields_have_errors($fields)) {
			try {
				AdministrateAPI::log('Add user ' . $fields['email']);
				$user = new AdministrateAPIUser(AdministrateAPI::make_soap_call('addUser', $this->prepare_object($fields)));
				return $user;
			} catch (Exception $e) {
				AdministrateAPI::log($e->getMessage(), 'error');
				return false;
			}
		} else {
			return false;	
		}	
	}
	
	//  Edit the user
	public function edit($fields = array()) {
		if (!$this->fields_have_errors($fields)) {
			try {
				$fields['id'] = $this->get_id();	
				AdministrateAPI::log('Edit user ID ' . $fields['id'] . ' successful');
				return AdministrateAPI::make_soap_call('editUser', $this->prepare_fields($fields));
			} catch (Exception $e) {
				AdministrateAPI::log($e->getMessage(), 'error');
				return false;
			}
		} else {
			return false;	
		}
	}
	
	/* **** DELEGATE MANAGEMENT *** */
	
	//  Get delegates
	public function get_delegates() {
		$this->_set_delegates();
		return $this->delegates;
	}
	
	//  Set delegates
	protected function _set_delegates() {
		if (!property_exists($this, 'delegates')) {
			$delegates = $this->_get_field('delegates');
			for ($i = 0, $numDelegates = count($delegates); $i < $numDelegates; ++$i) {
				$this->delegates[] = new AdministrateAPIDelegate($delegates[$i]);
			}
		}
	}
	
	//  Whether or not a delegate exists
	public function delegate_exists($email) {
		$this->_set_delegate_emails();
		return in_array($email, $this->delegateEmails);
	}
	
	//  Get a delegate by email address
	public function get_delegate_by_email($email) {
		// If we're looking for a delegate and we've provided
		// no email, return false immediately.
		if(empty($email)) {
			return false;
		}

		AdministrateAPI::log('Get delegate by email: ' . $email);
		$this->_set_delegate_emails();
		foreach($this->delegates as $delegate) {
			if($delegate->get_email() == $email) {
				return $delegate;
			}
		}
	}
	
	//  Set the delegate emails in an array
	public function _set_delegate_emails() {
		if (!property_exists($this, 'delegateEmails')) {
			$this->_set_delegates();
			$this->delegateEmails = array();
			foreach	($this->get_delegates() as $delegate) {
				array_push($this->delegateEmails, $delegate->get_email());
			}
		}	
	}
	
	//  Add a delegate
	public function add_delegate($fields = array()) {
		$delegate = new AdministrateAPIDelegate();
		$fields['user_id'] = $this->get_id();
		return $delegate->add($fields);
	}
	
}
