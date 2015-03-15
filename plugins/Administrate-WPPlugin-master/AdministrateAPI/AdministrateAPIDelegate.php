<?php
//  Administrate API Delegate
class AdministrateAPIDelegate extends AdministrateAPIObject {
	
	//  Properties
	protected $obj;
	protected $fields = array(
		'id'			=> array(
			'api_field'	=>	'WebsiteDelegateID'
		),
		'user_id'		=> array(
			'api_field'	=>	'WebsiteUserID',
			'default'	=>	0
		),
		'contact_id'	=> array(
			'api_field'	=>	'ContactID',
			'default'	=>	0
		),
		'email'	=> array(
			'api_field'	=>	'Email',
			'required'	=>	true
		),
		'mailing_list'	=> array(
			'api_field'	=>	'MailingList',
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
		'custom_text1'	=> array(
			'api_field'	=>	'CustomText1'
		),
		'custom_text2'	=> array(
			'api_field'	=>	'CustomText2'
		),
		'custom_text3'	=> array(
			'api_field'	=>	'CustomText3'
		),
		'custom_date1'	=> array(
			'api_field'	=>	'CustomDate1'
		),
		'custom_date2'	=> array(
			'api_field'	=>	'CustomDate2'
		),
		'notes'	=> array(
			'api_field'	=>	'DelegateNotes'
		)
	);

	//  Get the delegate's email address
	public function get_email() {
		return $this->_get_field('email');
	}

	//  Get the delegate's first name
	public function get_first_name() {
		return $this->_get_field('first_name');
	}

	//  Get the delegate's last name
	public function get_last_name() {
		return $this->_get_field('last_name');
	}
	
	//  Add a delegate
	public function add($fields = array()) {
		if (!$this->fields_have_errors($fields)) {
			try {
				AdministrateAPI::log('Add delegate ' . $fields['email']);
				$user = new AdministrateAPIUser(AdministrateAPI::make_soap_call('addDelegate', $this->prepare_object($fields)));
				foreach ($user->get_delegates() as $delegate) {

					// We've potentially added multiple delegates, and need to match the one we added
					// with one of the delegates that the APIUser now knows about to grab it's ID. Now
					// that emails aren't required, we need a more rigorous check to make sure we get
					// the correct $delegate.
					if ($delegate->get_first_name().$delegate->get_last_name().$delegate->get_email() == $fields['first_name'].$fields['last_name'].$fields['email']) {
						$del = $delegate;
					}
				}
				return $del;
			} catch (Exception $e) {
				AdministrateAPI::log($e->getMessage(), 'error');
				return false;
			}
		} else {
			return false;
		}	
	}
	
}
