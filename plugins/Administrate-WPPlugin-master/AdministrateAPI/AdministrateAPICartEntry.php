<?php
//  Administrate API Cart Entry
class AdministrateAPICartEntry extends AdministrateAPIObject {
	
	//  Properties
	protected $fields = array(
		'id'		=> array(
			'api_field'	=>	'WebsiteDelegateID'
		),
		'type'		=>	array(
			'api_field'	=>	'EntryType'
		),
		'amount'	=>	array(
			'api_field'	=>	'NetAmount'
		),
		'discount'	=>	array(
			'api_field'	=>	'DiscountRate',
			'default'	=>	0,
			'auto_fill'	=>	true
		),
		'tax_id'	=>	array(
			'api_field'	=>	'TaxID'
		)
	);
	
	//  Constructor
	public function __construct($obj = false) {
	
		//  Call parent constructor
		parent::__construct($obj);
		
		//  Merge fields with additionalFields
		$this->fields = array_merge($this->fields, $this->additionalFields);
		
	}
	
	//  Prepare fields
	public function prepare_fields($fields = array()) {
		
		//  Add the default type
		$fields['type'] = $this->fields['type']['default'];
		
		//  Call parent
		return parent::prepare_fields($fields);
			
	}
	
}