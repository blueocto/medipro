<?php
//  Administrate API Delegate Cart Entry
class AdministrateAPIDelegateCartEntry extends AdministrateAPICartEntry {
	
	//  Properties
	protected $additionalFields = array(
		'type'		=>	array(
			'api_field'	=>	'EntryType',
			'default'	=>	'delegate'
		),
		'event_id'	=>	array(
			'api_field'	=>	'EventID',
			'required'	=>	true
		),
		'delegate_id'	=>	array(
			'api_field'	=>	'WebsiteDelegateID',
			'required'	=>	true
		),
		'email'	=> array(
			'api_field'	=>	'Email'
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
		'notes'	=> array(
			'api_field'	=>	'DelegateNotes'
		)
	);
	
}
