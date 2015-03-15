<?php
//  Administrate API Order Payment
class AdministrateAPIOrderPayment extends AdministrateAPIObject {
	
	//  Properties
	protected $fields = array(
		'auth_code'			=>	array(
			'api_field'	=>	'AuthCode',
			'default'	=>	null,
			'auto_fill'	=>	true
		),
		'transaction_id'	=>	array(
			'api_field'	=>	'TransactionID',
			'default'	=>	null,
			'auto_fill'	=>	true
		),
		'card_type'			=>	array(
			'api_field'	=>	'CardType',
			'default'	=>	null,
			'auto_fill'	=>	true
		),
		'card_number'		=>	array(
			'api_field'	=>	'CardNumber',
			'default'	=>	null,
			'auto_fill'	=>	true
		)
	);
	
}