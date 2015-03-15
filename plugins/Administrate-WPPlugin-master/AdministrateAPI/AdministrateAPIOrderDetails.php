<?php
//  Administrate API Order Details
class AdministrateAPIOrderDetails extends AdministrateAPIObject {
	
	//  Properties
	protected $fields = array(
		'is_pending'		=> array(
			'api_field'		=>	'IsPending',
			'required'		=>	true,
			'default'		=>	false
		),
		'date'		=>	array(
			'api_field'		=>	'OrderDate',
			'time_format'	=>	'Y-m-d'
		),
		'user_id'			=>	array(
			'api_field'	=>	'WebsiteUserID'
		),
		'payment_method'	=>	array(
			'api_field'	=>	'PaymentMethod',
			'mappings'	=>	array(
				'I'	=>	'invoice',
				'C'	=>	'card'
			)
		),
		'currency'			=>	array(
			'api_field'	=>	'Currency'
		),
		'region'			=>	array(
			'api_field'	=>	'Region'
		),
		'email'				=>	array(
			'api_field'	=>	'Email'
		),
		'company'			=>	array(
			'api_field'	=>	'Company'
		),
		'first_name'			=>	array(
			'api_field'	=>	'FirstName'
		),
		'last_name'			=>	array(
			'api_field'	=>	'LastName'
		),
		'phone'				=>	array(
			'api_field'	=>	'Tel'
		),
		'address1'			=>	array(
			'api_field'	=>	'Address1'
		),
		'city'				=>	array(
			'api_field'	=>	'City'
		),
		'territory'			=>	array(
			'api_field'	=>	'County'
		),
		'postal_code'		=>	array(
			'api_field'	=>	'PostCode'
		),
		'country'			=>	array(
			'api_field'	=>	'Country'
		),
		'notes'			=>	array(
			'api_field'	=>	'OrderNotes'
		)
	);
	
}
